<?php

namespace App\Console\Commands;

use App\Models\{ContentItem,Instructor,LearningSection,Program,Survey,PracticeCycle};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportLegacyNmoContent extends Command
{
    protected $signature = 'legacy:import-nmo-content
        {--dry-run}
        {--nmo-dir= : Корень legacy папки nmo}
        {--notebook-templates-dir= : Legacy tetrad/shabl}';

    protected $description = 'Import tm_nmo_razd/add_nmo.php course structure, media metadata and legacy files';

    private const TYPES = [
        1=>'document',2=>'video',3=>'test',4=>'control_work',5=>'completion',6=>'questionnaire',7=>'file',
        10=>'response',11=>'payment',12=>'link',15=>'certificate_test',16=>'practice',17=>'notebook',18=>'questionnaire_test',
        19=>'table',20=>'random',21=>'test_answers',22=>'exam'
    ];

    public function handle(): int
    {
        $db=DB::connection('legacy');$schema=$db->getSchemaBuilder();
        foreach(['tm_nmo_razd','tm_nmo_razd_media','tm_nmo_razd_media_list','tm_nmo_sert_test','tm_nmo_razd_dop_prepod'] as $table){
            $this->line(($schema->hasTable($table)?'[+] ':'[-] ').$table.($schema->hasTable($table)?' — '.$db->table($table)->count():'') );
        }
        if(!$schema->hasTable('tm_nmo_razd')){$this->warn('tm_nmo_razd отсутствует — импорт конструктора пропущен.');return self::SUCCESS;}
        if($this->option('dry-run'))return self::SUCCESS;

        $programs=Program::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $instructors=Instructor::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $surveys=Survey::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $practices=PracticeCycle::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $nmoDir=$this->option('nmo-dir')?:base_path('../nmo');
        $notebookDir=$this->option('notebook-templates-dir')?:base_path('../tetrad/shabl');

        $this->info('Importing NMO sections...');
        $sectionCount=0;
        foreach($db->table('tm_nmo_razd')->orderBy('id')->get() as $row){
            $programId=$programs[(int)($row->spec??0)]??null;if(!$programId)continue;
            $section=LearningSection::updateOrCreate(['legacy_id'=>(int)$row->id],[
                'program_id'=>$programId,'title'=>trim((string)($row->nazv??''))?:('Раздел '.$row->id),
                'description'=>$row->comment??null,'position'=>(int)($row->num??0),'is_active'=>(bool)($row->activ??1),
                'is_required'=>true,'type'=>'material','image_path'=>$this->importSectionImage($nmoDir,$row->img??null),
                'settings'=>['legacy_prepod'=>$row->prepod??null],
            ]);
            $sync=[];$primaryLegacy=(int)($row->prepod??0);if($primaryLegacy&&isset($instructors[$primaryLegacy]))$sync[$instructors[$primaryLegacy]]=['is_primary'=>true];
            if($schema->hasTable('tm_nmo_razd_dop_prepod'))foreach($db->table('tm_nmo_razd_dop_prepod')->where('razdel',$row->id)->get() as $extra){$lid=(int)($extra->prepod??0);if($lid&&isset($instructors[$lid])&&!isset($sync[$instructors[$lid]]))$sync[$instructors[$lid]]=['is_primary'=>false];}
            $section->instructors()->sync($sync);$sectionCount++;
        }
        $sections=LearningSection::whereNotNull('legacy_id')->pluck('id','legacy_id');

        $this->info('Importing NMO materials...');$itemCount=0;$fileCount=0;
        if($schema->hasTable('tm_nmo_razd_media')){
            foreach($db->table('tm_nmo_razd_media')->orderBy('id')->get() as $row){
                $sectionId=$sections[(int)($row->tm_nmo_razd??0)]??null;if(!$sectionId)continue;
                $legacyType=(int)($row->tip??0);$type=self::TYPES[$legacyType]??('legacy_'.$legacyType);$settings=[];
                if($legacyType===4&&$schema->hasTable('tm_nmo_razd_media_list'))$settings['list_options']=$db->table('tm_nmo_razd_media_list')->where('tm_nmo_razd_media',$row->id)->orderBy('id')->pluck('tex')->filter(fn($v)=>$v!==null&&trim((string)$v)!=='')->values()->all();
                if(in_array($legacyType,[15,22],true)&&$schema->hasTable('tm_nmo_sert_test'))if($cert=$db->table('tm_nmo_sert_test')->where('media',$row->id)->first())$settings+=['certificate_name'=>$cert->nazv??null,'certificate_text'=>$cert->text??null,'certificate_hours'=>isset($cert->chas)?(int)$cert->chas:null];
                if($legacyType===11&&isset($row->dop_file)&&is_numeric(str_replace(',','.',(string)$row->dop_file)))$settings['price']=(float)str_replace(',','.',(string)$row->dop_file);
                if($legacyType===6){$legacySurvey=(int)($row->path??0);$settings['legacy_survey_id']=$legacySurvey;if(isset($surveys[$legacySurvey]))$settings['survey_id']=$surveys[$legacySurvey];}
                if($legacyType===16){$legacyPractice=(int)($row->path??0);$settings['legacy_practice_id']=$legacyPractice;if(isset($practices[$legacyPractice]))$settings['practice_cycle_id']=$practices[$legacyPractice];}
                if($legacyType===20)$settings['legacy_random_source']=$row->path??null;

                [$filePath,$packageRoot,$external]=$this->importMain($legacyType,$row->path??null,$nmoDir,$notebookDir);
                if($packageRoot)$settings['package_root']=$packageRoot;
                if($filePath)$fileCount++;
                $extraPath=$this->importExtra($legacyType,$row->dop_file??null,$nmoDir);
                if($extraPath)$fileCount++;
                if($legacyType===18&&isset($row->comment)&&$this->looksLikeFile((string)$row->comment)){
                    $handler=$this->copyLegacyFile($nmoDir.DIRECTORY_SEPARATOR.'obr'.DIRECTORY_SEPARATOR.ltrim((string)$row->comment,'/\\'),'nmo/imported/handlers');
                    if($handler){$settings['legacy_handler_path']=$handler;$settings['handler_execution']='disabled_for_security';$fileCount++;}
                }
                $body=$row->comment??null;if($legacyType===18&&isset($settings['legacy_handler_path']))$body=null;
                ContentItem::updateOrCreate(['legacy_id'=>(int)$row->id],[
                    'learning_section_id'=>$sectionId,'title'=>trim((string)($row->nazv??''))?:('Элемент '.$row->id),
                    'type'=>$type,'legacy_type'=>$legacyType,'body'=>$body,'file_path'=>$filePath,'extra_file_path'=>$extraPath,
                    'external_url'=>$external,'position'=>(int)($row->num??0),'is_active'=>(bool)($row->act??1),
                    'is_required'=>(bool)($row->obyaz??0),'allow_duplicate'=>(bool)($row->povt??0),'flag'=>(bool)($row->gal??0),
                    'available_from'=>$row->data_act??null,'available_until'=>$row->data_okon??null,'settings'=>$settings,
                ]);$itemCount++;
            }
        }
        $this->info("NMO constructor imported: sections={$sectionCount}, items={$itemCount}, copied files/packages={$fileCount}");
        return self::SUCCESS;
    }

    private function importMain(int $type,$path,string $nmoDir,string $notebookDir): array
    {
        $path=trim((string)$path);if($path==='')return [null,null,null];
        if(in_array($type,[2,12],true)&&preg_match('~^https?://~i',$path))return [null,null,$path];
        if(in_array($type,[2,12],true))return [null,null,$path];
        if(in_array($type,[6,7,11,16,20],true))return [null,null,null];
        $base=match($type){1=>$nmoDir.DIRECTORY_SEPARATOR.'doc',19=>$nmoDir.DIRECTORY_SEPARATOR.'csv',17=>$notebookDir,default=>$nmoDir.DIRECTORY_SEPARATOR.'test'};
        $source=$base.DIRECTORY_SEPARATOR.str_replace(['/', '\\'],DIRECTORY_SEPARATOR,$path);
        if(in_array($type,[3,15,17,18,21,22],true)){
            $root=dirname($source);if(is_file($source)&&in_array(strtolower(pathinfo($source,PATHINFO_EXTENSION)),['html','htm'],true)){
                $destRoot='nmo/imported/packages/'.Str::uuid();$this->copyDirectory($root,$destRoot);$rel=str_replace('\\','/',substr($source,strlen(rtrim($root,'/\\'))+1));return [$destRoot.'/'.$rel,$destRoot,null];
            }
        }
        return [$this->copyLegacyFile($source,'nmo/imported/items'),null,null];
    }

    private function importExtra(int $type,$path,string $nmoDir): ?string
    {
        $path=trim((string)$path);if($path===''||strtolower($path)==='null'||($type===11&&is_numeric(str_replace(',','.',$path))))return null;
        $base=match($type){21=>$nmoDir.DIRECTORY_SEPARATOR.'doc',15,22=>$nmoDir,default=>null};if(!$base)return null;
        return $this->copyLegacyFile($base.DIRECTORY_SEPARATOR.str_replace(['/', '\\'],DIRECTORY_SEPARATOR,$path),'nmo/imported/extra');
    }

    private function importSectionImage(string $nmoDir,$image): ?string
    {
        $image=trim((string)$image);if($image===''||$image==='-')return null;
        return $this->copyLegacyFile($nmoDir.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.basename($image),'nmo/imported/sections');
    }

    private function copyLegacyFile(string $source,string $destDir): ?string
    {
        if(!is_file($source))return null;$name=Str::uuid().'-'.basename($source);$dest=$destDir.'/'.$name;Storage::put($dest,File::get($source));return $dest;
    }

    private function copyDirectory(string $sourceDir,string $destRoot): void
    {
        if(!is_dir($sourceDir))return;
        foreach(File::allFiles($sourceDir) as $file){$rel=str_replace('\\','/',$file->getRelativePathname());if(str_contains($rel,'../'))continue;Storage::put($destRoot.'/'.$rel,File::get($file->getPathname()));}
    }

    private function looksLikeFile(string $value): bool{return (bool)preg_match('/\.(php|inc|txt)$/i',trim($value));}
}