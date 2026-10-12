<?php
namespace App\Http\Controllers;
use App\Models\{ContentItem,ContentItemResponse,Enrollment,LearningSection,SectionProgress,QuizAssignment,PracticeAssignment,QuizAttempt,Submission};
use App\Services\{CompletionService,LearningAccessService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LearningController extends Controller {
 public function index(Request $request,LearningAccessService $access){
  $programIds=$request->user()->enrollments()->where('status','active')->pluck('program_id');
  $sections=LearningSection::with('program')->whereIn('program_id',$programIds)->where('is_active',true)->orderBy('program_id')->orderBy('position')->get();
  $progress=SectionProgress::where('user_id',$request->user()->id)->whereIn('learning_section_id',$sections->pluck('id'))->get()->keyBy('learning_section_id');
  foreach($sections as $section)$section->setAttribute('can_open',$access->canOpenSection($request->user(),$section));
  return view('learning.index',compact('sections','progress'));
 }
 public function show(Request $request,LearningSection $section,LearningAccessService $access){
  abort_unless($access->canOpenSection($request->user(),$section),403,'Раздел пока недоступен.');
  $section->load('program');
  $section->load(['contentItems'=>fn($q)=>$q
   ->where('is_active',true)
   ->where(function($x){$x->whereNull('available_from')->orWhere('available_from','<=',now());})
   ->where(function($x){$x->whereNull('available_until')->orWhere('available_until','>=',now());})
   ->orderBy('position')]);
  $progress=SectionProgress::firstOrCreate(['user_id'=>$request->user()->id,'learning_section_id'=>$section->id],['progress_percent'=>0]);
  $quizzes=QuizAssignment::with('quiz')->where('program_id',$section->program_id)->where('learning_section_id',$section->id)->where('is_active',true)->get();
  $quizAttempts=QuizAttempt::where('user_id',$request->user()->id)->whereIn('quiz_assignment_id',$quizzes->pluck('id'))->latest('finished_at')->get()->groupBy('quiz_assignment_id');
  $practice=PracticeAssignment::where('program_id',$section->program_id)->where('learning_section_id',$section->id)->where('is_active',true)->get();
  $submissions=Submission::where('user_id',$request->user()->id)->whereIn('practice_assignment_id',$practice->pluck('id'))->get()->keyBy('practice_assignment_id');
  $responses=ContentItemResponse::where('user_id',$request->user()->id)->whereIn('content_item_id',$section->contentItems->pluck('id'))->get()->keyBy('content_item_id');
  return view('learning.show',compact('section','progress','quizzes','quizAttempts','practice','submissions','responses'));
 }
 public function complete(Request $request,LearningSection $section,LearningAccessService $access){
  abort_unless($access->canOpenSection($request->user(),$section),403);
  SectionProgress::updateOrCreate(['user_id'=>$request->user()->id,'learning_section_id'=>$section->id],['progress_percent'=>100,'completed_at'=>now()]);
  return back()->with('success','Раздел отмечен как пройденный.');
 }
 public function download(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  abort_unless($item->file_path && Storage::exists($item->file_path),404);
  $ext=strtolower(pathinfo($item->file_path,PATHINFO_EXTENSION));
  if(in_array($ext,['html','htm'],true))return response(Storage::get($item->file_path),200,['Content-Type'=>'text/html; charset=UTF-8','X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; img-src 'self' data: blob:; media-src 'self' data: blob:;"]);
  return Storage::download($item->file_path);
 }
 public function package(Request $request,ContentItem $item,LearningAccessService $access,?string $path=null){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  $settings=$item->settings?:[];$root=$settings['package_root']??null;abort_unless($root,404);
  $relative=$path?:ltrim(str_replace($root.'/','',(string)$item->file_path),'/');
  $relative=str_replace('\\','/',$relative);abort_if(str_starts_with($relative,'/')||preg_match('/(^|\/)\.\.(\/|$)/',$relative),400);
  $full=rtrim($root,'/').'/'.ltrim($relative,'/');abort_unless(Storage::exists($full),404);
  $ext=strtolower(pathinfo($full,PATHINFO_EXTENSION));$mime=match($ext){'html','htm'=>'text/html; charset=UTF-8','js'=>'application/javascript','css'=>'text/css','json'=>'application/json','svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','gif'=>'image/gif','webp'=>'image/webp','mp3'=>'audio/mpeg','mp4'=>'video/mp4','pdf'=>'application/pdf',default=>'application/octet-stream'};
  return response(Storage::get($full),200,['Content-Type'=>$mime,'X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; img-src 'self' data: blob:; media-src 'self' data: blob:;"]);
 }


 public function completeProgram(Request $request,ContentItem $item,LearningAccessService $access,CompletionService $completion){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  abort_unless((int)$item->legacy_type===5,422,'Этот элемент не завершает обучение.');
  $enrollment=Enrollment::where('user_id',$request->user()->id)->where('program_id',$item->section->program_id)->where('status','active')->firstOrFail();
  $enrollment->load('program');
  $completion->complete($enrollment);
  return redirect()->route('programs.show',$enrollment)->with('ok','Программа успешно завершена.');
 }

 public function submitResponse(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  $legacy=(int)$item->legacy_type;
  abort_unless(in_array($legacy,[4,7,10,17,20],true),422,'Этот тип материала не принимает ответ.');
  $data=$request->validate([
   'text'=>['nullable','string','max:50000'],
   'file'=>['nullable','file','max:51200'],
   'files'=>['nullable','array','max:12'],
   'files.*'=>['file','max:51200'],
   'action'=>['nullable','string','max:30'],
  ]);
  $response=ContentItemResponse::firstOrNew(['content_item_id'=>$item->id,'user_id'=>$request->user()->id]);
  $meta=$response->meta?:[];
  if($legacy===20){
   if(!isset($meta['random_value']) || $request->input('action')==='reroll'){
    $set=$item->settings?:[];$min=(int)($set['random_min']??1);$max=(int)($set['random_max']??100);if($max<$min){$t=$min;$min=$max;$max=$t;}
    $meta['random_value']=random_int($min,$max);$meta['generated_at']=now()->toIso8601String();
   }
   $response->meta=$meta;$response->status='submitted';$response->submitted_at=now();$response->save();
   return back()->with('ok','Случайное значение закреплено за вами: '.$meta['random_value']);
  }
  if($request->hasFile('file')){
   if($response->file_path)Storage::delete($response->file_path);
   $response->file_path=$request->file('file')->store('content-responses/'.$request->user()->id.'/'.$item->id);
  }
  if($request->hasFile('files')){
   foreach((array)$response->files as $old)if(is_string($old))Storage::delete($old);
   $paths=[];foreach($request->file('files',[]) as $file)$paths[]=$file->store('content-responses/'.$request->user()->id.'/'.$item->id);
   $response->files=$paths;
  }
  $response->text=$data['text']??$response->text;
  $response->status='submitted';$response->submitted_at=now();$response->reviewed_by=null;$response->reviewed_at=null;$response->review_comment=null;$response->save();
  return back()->with('ok','Ответ сохранён и отправлен куратору.');
 }

 public function downloadResponse(Request $request,ContentItemResponse $response,int $index=-1){
  $allowed=$response->user_id===$request->user()->id || in_array($request->user()->role,['curator','admin'],true);abort_unless($allowed,403);
  $path=$index>=0?($response->files[$index]??null):$response->file_path;abort_unless($path&&Storage::exists($path),404);
  return Storage::download($path);
 }

 private function authorizeItem(Request $request,ContentItem $item,LearningAccessService $access):void{
  $section=$item->section;abort_unless($section&&$item->is_active&&$access->canOpenSection($request->user(),$section),403);
  if($item->available_from&&now()->lt($item->available_from))abort(403,'Материал ещё не открыт.');
  if($item->available_until&&now()->gt($item->available_until))abort(403,'Срок доступа к материалу завершён.');
 }
}
