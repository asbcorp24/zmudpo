<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ContentItem,Instructor,LearningSection,PracticeCycle,Program,Survey};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use ZipArchive;

class ContentAdminController extends Controller
{
    private const LEGACY_TYPES = [
        1 => ['type'=>'document','name'=>'Документ'],
        2 => ['type'=>'video','name'=>'Видео'],
        3 => ['type'=>'test','name'=>'Тест'],
        4 => ['type'=>'control_work','name'=>'Контрольная работа'],
        5 => ['type'=>'completion','name'=>'Завершение работы'],
        6 => ['type'=>'questionnaire','name'=>'Анкета'],
        7 => ['type'=>'file','name'=>'Файл / поле'],
        10 => ['type'=>'response','name'=>'Набор скриншотов или текстов'],
        11 => ['type'=>'payment','name'=>'Оплата раздела'],
        12 => ['type'=>'link','name'=>'Ссылка'],
        15 => ['type'=>'certificate_test','name'=>'Тест с сертификатом'],
        16 => ['type'=>'practice','name'=>'Практика'],
        17 => ['type'=>'notebook','name'=>'Рабочая тетрадь'],
        18 => ['type'=>'questionnaire_test','name'=>'Анкетный тест'],
        19 => ['type'=>'table','name'=>'Таблица CSV'],
        20 => ['type'=>'random','name'=>'Случайное число'],
        21 => ['type'=>'test_answers','name'=>'Тест с ответами'],
        22 => ['type'=>'exam','name'=>'Экзамен'],
    ];

    public function index(Request $request)
    {
        $programs = Program::with('type')->orderBy('title')->get();
        $program = $request->filled('program_id') ? Program::find($request->integer('program_id')) : $programs->first();
        [$sections,$instructors,$surveys,$practiceCycles,$allSections] = $this->workspaceData($program);
        $legacyTypes = self::LEGACY_TYPES;

        if ($request->ajax()) {
            return view('admin.legacy._nmo-workspace', compact('program','sections','programs','instructors','surveys','practiceCycles','allSections','legacyTypes'));
        }

        return view('admin.legacy.learning-content', compact('programs','program','sections','instructors','surveys','practiceCycles','allSections','legacyTypes'));
    }

    public function section(Request $request)
    {
        $data = $request->validate([
            'program_id'=>'required|exists:programs,id',
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'position'=>'nullable|integer|min:0',
            'image'=>'nullable|image|max:10240',
            'primary_instructor_id'=>'nullable|exists:instructors,id',
            'instructor_ids'=>'nullable|array',
            'instructor_ids.*'=>'exists:instructors,id',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('nmo/sections') : null;
        $section = LearningSection::create([
            'program_id'=>$data['program_id'],
            'title'=>trim($data['title']),
            'description'=>$data['description'] ?? null,
            'position'=>$data['position'] ?? 0,
            'image_path'=>$imagePath,
            'type'=>'material',
            'is_active'=>$request->boolean('is_active', true),
            'is_required'=>$request->boolean('is_required'),
        ]);
        $this->syncInstructors($section,$data['primary_instructor_id'] ?? null,$data['instructor_ids'] ?? []);
        return $this->ok($request,'Раздел создан',['id'=>$section->id,'program_id'=>$section->program_id]);
    }

    public function updateSection(Request $request, LearningSection $section)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'position'=>'nullable|integer|min:0',
            'image'=>'nullable|image|max:10240',
            'primary_instructor_id'=>'nullable|exists:instructors,id',
            'instructor_ids'=>'nullable|array',
            'instructor_ids.*'=>'exists:instructors,id',
        ]);
        if ($request->hasFile('image')) {
            if ($section->image_path) Storage::delete($section->image_path);
            $data['image_path']=$request->file('image')->store('nmo/sections');
        }
        unset($data['image'],$data['primary_instructor_id'],$data['instructor_ids']);
        $section->update($data+[
            'is_active'=>$request->boolean('is_active'),
            'is_required'=>$request->boolean('is_required'),
        ]);
        $this->syncInstructors($section,$request->input('primary_instructor_id'),$request->input('instructor_ids',[]));
        return $this->ok($request,'Раздел обновлён',['id'=>$section->id]);
    }

    public function destroySection(Request $request, LearningSection $section)
    {
        foreach ($section->contentItems as $item) $this->deleteItemFiles($item);
        if ($section->image_path) Storage::delete($section->image_path);
        $programId=$section->program_id;
        $section->delete();
        return $this->ok($request,'Раздел удалён',['program_id'=>$programId]);
    }

    public function item(Request $request)
    {
        $data = $this->validateItem($request);
        $legacyType=(int)$data['legacy_type'];
        $map=self::LEGACY_TYPES[$legacyType] ?? null;
        abort_unless($map,422,'Неизвестный тип элемента');

        $files=$request->file('files',[]);
        if (!is_array($files)) $files=[$files];
        if ($legacyType===1 && count(array_filter($files))>1) {
            $created=[];
            DB::transaction(function() use ($request,$data,$map,$files,&$created) {
                foreach(array_filter($files) as $file){
                    $row=$data;
                    $row['title']=trim($data['title'] ?: pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME));
                    if(count($files)>1)$row['title']=pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                    $created[]=$this->createItem($request,$row,$map,$file);
                }
            });
            return $this->ok($request,'Документы загружены: '.count($created),['ids'=>collect($created)->pluck('id')]);
        }

        $item=$this->createItem($request,$data,$map,$files[0] ?? $request->file('file'));
        return $this->ok($request,'Учебный элемент добавлен',['id'=>$item->id,'section_id'=>$item->learning_section_id]);
    }

    public function update(Request $request, ContentItem $item)
    {
        $data=$request->validate([
            'title'=>'required|string|max:255','body'=>'nullable|string','position'=>'nullable|integer|min:0',
            'available_from'=>'nullable|date','available_until'=>'nullable|date|after_or_equal:available_from',
            'repeat_limit'=>'nullable|integer|min:1','external_url'=>'nullable|url|max:1000',
            'file'=>'nullable|file|max:102400','extra_file'=>'nullable|file|max:102400',
            'certificate_name'=>'nullable|string|max:255','certificate_text'=>'nullable|string','certificate_hours'=>'nullable|integer|min:0',
            'list_options'=>'nullable|string','price'=>'nullable|numeric|min:0','survey_id'=>'nullable|integer','practice_cycle_id'=>'nullable|integer',
            'random_min'=>'nullable|integer','random_max'=>'nullable|integer',
        ]);

        $settings=$item->settings ?: [];
        foreach(['certificate_name','certificate_text','certificate_hours','price','survey_id','practice_cycle_id','random_min','random_max'] as $key){
            if($request->has($key))$settings[$key]=$data[$key] ?? null;
        }
        if($request->has('list_options'))$settings['list_options']=$this->lines($data['list_options'] ?? '');

        if($request->hasFile('file')){
            if($item->file_path)$this->deleteStoredPath($item->file_path,$settings['package_root'] ?? null);
            [$path,$packageRoot]=$this->storeContentFile($request->file('file'),in_array((int)$item->legacy_type,[3,15,17,18,21,22],true));
            $data['file_path']=$path;
            if($packageRoot)$settings['package_root']=$packageRoot;else unset($settings['package_root']);
        }
        if($request->hasFile('extra_file')){
            if($item->extra_file_path)Storage::delete($item->extra_file_path);
            $data['extra_file_path']=$request->file('extra_file')->store('nmo/extra');
        }
        unset($data['file'],$data['extra_file'],$data['certificate_name'],$data['certificate_text'],$data['certificate_hours'],$data['list_options'],$data['price'],$data['survey_id'],$data['practice_cycle_id'],$data['random_min'],$data['random_max']);
        $item->update($data+[
            'settings'=>$settings,
            'is_active'=>$request->boolean('is_active'),
            'is_required'=>$request->boolean('is_required'),
            'allow_duplicate'=>$request->boolean('allow_duplicate'),
            'flag'=>$request->boolean('flag'),
        ]);
        return $this->ok($request,'Элемент обновлён',['id'=>$item->id]);
    }

    public function destroyItem(Request $request, ContentItem $item)
    {
        $sectionId=$item->learning_section_id;
        $this->deleteItemFiles($item);
        $item->delete();
        return $this->ok($request,'Элемент удалён',['section_id'=>$sectionId]);
    }

    public function reorder(Request $request)
    {
        $data=$request->validate(['kind'=>'required|in:sections,items','ids'=>'required|array','ids.*'=>'integer']);
        $model=$data['kind']==='sections'?LearningSection::class:ContentItem::class;
        DB::transaction(function()use($data,$model){foreach($data['ids'] as $position=>$id)$model::whereKey($id)->update(['position'=>$position+1]);});
        return $this->ok($request,'Порядок сохранён');
    }

    public function copySection(Request $request, LearningSection $section)
    {
        $data=$request->validate(['program_id'=>'required|exists:programs,id']);
        $copy=null;
        DB::transaction(function()use($section,$data,&$copy){
            $section->load(['contentItems','instructors']);
            $copy=$section->replicate(['legacy_id']);$copy->program_id=$data['program_id'];$copy->title.=' (копия)';$copy->position=0;$copy->save();
            foreach($section->contentItems as $item){$x=$item->replicate(['legacy_id']);$x->learning_section_id=$copy->id;$x->save();}
            foreach($section->instructors as $instructor)$copy->instructors()->attach($instructor->id,['is_primary'=>(bool)$instructor->pivot->is_primary]);
        });
        return $this->ok($request,'Раздел и все его элементы скопированы',['id'=>$copy?->id,'program_id'=>$copy?->program_id]);
    }

    public function copyItem(Request $request, ContentItem $item)
    {
        $data=$request->validate(['learning_section_id'=>'required|exists:learning_sections,id']);
        $x=$item->replicate(['legacy_id']);$x->learning_section_id=$data['learning_section_id'];$x->title.=' (копия)';$x->save();
        return $this->ok($request,'Материал скопирован',['id'=>$x->id]);
    }

    public function copyItems(Request $request)
    {
        $data=$request->validate(['ids'=>'required|array|min:1','ids.*'=>'exists:content_items,id','learning_section_id'=>'required|exists:learning_sections,id']);
        $count=0;
        DB::transaction(function()use($data,&$count){foreach(ContentItem::whereIn('id',$data['ids'])->get() as $item){$x=$item->replicate(['legacy_id']);$x->learning_section_id=$data['learning_section_id'];$x->save();$count++;}});
        return $this->ok($request,"Скопировано элементов: {$count}");
    }

    public function addDefaults(Request $request)
    {
        $data=$request->validate(['program_id'=>'required|exists:programs,id','kind'=>'required|in:response,questionnaire','survey_id'=>'nullable|exists:surveys,id']);
        $sections=LearningSection::where('program_id',$data['program_id'])->get();$count=0;
        foreach($sections as $section){
            $legacy=$data['kind']==='response'?10:6;
            if(ContentItem::where('learning_section_id',$section->id)->where('legacy_type',$legacy)->exists())continue;
            $settings=$legacy===6?['survey_id'=>$data['survey_id'] ?? null]:[];
            ContentItem::create(['learning_section_id'=>$section->id,'title'=>$legacy===10?'Решение заданий':'Анкета','type'=>self::LEGACY_TYPES[$legacy]['type'],'legacy_type'=>$legacy,'body'=>$legacy===10?'Добавляйте скриншоты или текст':'Заполните поля','position'=>1000,'is_active'=>false,'is_required'=>true,'settings'=>$settings]);$count++;
        }
        return $this->ok($request,"Добавлено элементов: {$count}");
    }

    public function adminDownload(ContentItem $item, string $which='main')
    {
        $path=$which==='extra'?$item->extra_file_path:$item->file_path;
        abort_unless($path && Storage::exists($path),404);
        return Storage::download($path);
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'learning_section_id'=>'required|exists:learning_sections,id','legacy_type'=>['required','integer',Rule::in(array_keys(self::LEGACY_TYPES))],
            'title'=>'nullable|string|max:255','body'=>'nullable|string','position'=>'nullable|integer|min:0','external_url'=>'nullable|url|max:1000',
            'available_from'=>'nullable|date','available_until'=>'nullable|date|after_or_equal:available_from','repeat_limit'=>'nullable|integer|min:1',
            'files'=>'nullable|array','files.*'=>'file|max:102400','file'=>'nullable|file|max:102400','extra_file'=>'nullable|file|max:102400','handler_file'=>'nullable|file|max:10240',
            'certificate_name'=>'nullable|string|max:255','certificate_text'=>'nullable|string','certificate_hours'=>'nullable|integer|min:0',
            'list_options'=>'nullable|string','price'=>'nullable|numeric|min:0','survey_id'=>'nullable|exists:surveys,id','practice_cycle_id'=>'nullable|exists:practice_cycles,id',
            'random_min'=>'nullable|integer','random_max'=>'nullable|integer','source_value'=>'nullable|string|max:1000',
        ]);
    }

    private function createItem(Request $request,array $data,array $map,$file=null): ContentItem
    {
        $legacy=(int)$data['legacy_type'];$settings=[];$filePath=null;$packageRoot=null;$extraPath=null;
        if($file){[$filePath,$packageRoot]=$this->storeContentFile($file,in_array($legacy,[3,15,17,18,21,22],true));}
        if($request->hasFile('extra_file'))$extraPath=$request->file('extra_file')->store('nmo/extra');
        if($request->hasFile('handler_file'))$settings['legacy_handler_path']=$request->file('handler_file')->store('nmo/handlers');
        if($packageRoot)$settings['package_root']=$packageRoot;
        if(!empty($data['list_options']))$settings['list_options']=$this->lines($data['list_options']);
        foreach(['certificate_name','certificate_text','certificate_hours','price','survey_id','practice_cycle_id','random_min','random_max','source_value'] as $key)if(array_key_exists($key,$data)&&$data[$key]!==null&&$data[$key]!=='')$settings[$key]=$data[$key];
        if($legacy===18 && isset($settings['legacy_handler_path']))$settings['handler_execution']='disabled_for_security';
        $title=trim((string)($data['title'] ?? ''));
        if($title==='')$title=$file?pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME):$map['name'];
        return ContentItem::create([
            'learning_section_id'=>$data['learning_section_id'],'title'=>$title,'type'=>$map['type'],'legacy_type'=>$legacy,
            'body'=>$data['body'] ?? null,'file_path'=>$filePath,'extra_file_path'=>$extraPath,
            'external_url'=>$data['external_url'] ?? ($legacy===2||$legacy===12?($data['source_value'] ?? null):null),
            'position'=>$data['position'] ?? 0,'available_from'=>$data['available_from'] ?? null,'available_until'=>$data['available_until'] ?? null,
            'repeat_limit'=>$data['repeat_limit'] ?? null,'is_required'=>$request->boolean('is_required'),'is_active'=>$request->boolean('is_active',true),
            'allow_duplicate'=>$request->boolean('allow_duplicate'),'flag'=>$request->boolean('flag'),'settings'=>$settings,
        ]);
    }

    private function storeContentFile($file,bool $package): array
    {
        if(!$package || strtolower($file->getClientOriginalExtension())!=='zip')return [$file->store('nmo/items'),null];
        $zip=new ZipArchive();abort_if($zip->open($file->getRealPath())!==true,422,'Не удалось открыть ZIP');
        $root='nmo/packages/'.Str::uuid();$html=[];
        for($i=0;$i<$zip->numFiles;$i++){
            $name=str_replace('\\','/',$zip->getNameIndex($i));
            if($name===''||str_ends_with($name,'/'))continue;
            abort_if(str_starts_with($name,'/')||preg_match('/(^|\/)\.\.(\/|$)/',$name)||preg_match('/^[A-Za-z]:/',$name),422,'ZIP содержит небезопасный путь');
            $name=ltrim($name,'/');$bytes=$zip->getFromIndex($i);Storage::put($root.'/'.$name,$bytes);
            if(preg_match('/\.html?$/i',$name))$html[]=$name;
        }
        $zip->close();
        $index=collect($html)->first(fn($x)=>strtolower(basename($x))==='index.html') ?? ($html[0] ?? null);
        abort_unless($index,422,'В ZIP не найден HTML-файл');
        return [$root.'/'.$index,$root];
    }

    private function syncInstructors(LearningSection $section,$primary,$additional): void
    {
        $sync=[];
        if($primary)$sync[(int)$primary]=['is_primary'=>true];
        foreach((array)$additional as $id)if((int)$id && (int)$id!==(int)$primary)$sync[(int)$id]=['is_primary'=>false];
        $section->instructors()->sync($sync);
    }

    private function workspaceData(?Program $program): array
    {
        $sections=$program?LearningSection::with(['contentItems','instructors'])->where('program_id',$program->id)->orderBy('position')->get():collect();
        return [$sections,Instructor::where('is_active',true)->orderBy('full_name')->get(),Survey::where('is_active',true)->orderBy('title')->get(),PracticeCycle::where('is_active',true)->orderBy('title')->get(),LearningSection::with('program')->orderBy('program_id')->orderBy('position')->get()];
    }

    private function deleteItemFiles(ContentItem $item): void
    {
        $settings=$item->settings ?: [];
        $this->deleteStoredPath($item->file_path,$settings['package_root'] ?? null);
        foreach([$item->extra_file_path,$settings['legacy_handler_path'] ?? null] as $path)if($path)Storage::delete($path);
    }

    private function deleteStoredPath($path,$packageRoot=null): void
    {
        if($packageRoot)Storage::deleteDirectory($packageRoot);elseif($path)Storage::delete($path);
    }

    private function lines(string $value): array{return array_values(array_filter(array_map('trim',preg_split('/\R/u',$value)),fn($v)=>$v!==''));}

    private function ok(Request $request,string $message,array $extra=[])
    {
        if($request->expectsJson())return response()->json(['ok'=>true,'message'=>$message]+$extra);
        return back()->with('ok',$message);
    }
}