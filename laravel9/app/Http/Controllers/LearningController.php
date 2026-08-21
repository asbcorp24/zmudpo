<?php
namespace App\Http\Controllers;
use App\Models\{ContentItem,LearningSection,SectionProgress,QuizAssignment,PracticeAssignment,QuizAttempt,Submission};
use App\Services\LearningAccessService;
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
  return view('learning.show',compact('section','progress','quizzes','quizAttempts','practice','submissions'));
 }
 public function complete(Request $request,LearningSection $section,LearningAccessService $access){
  abort_unless($access->canOpenSection($request->user(),$section),403);
  SectionProgress::updateOrCreate(['user_id'=>$request->user()->id,'learning_section_id'=>$section->id],['progress_percent'=>100,'completed_at'=>now()]);
  return back()->with('success','Раздел отмечен как пройденный.');
 }
 public function download(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  abort_unless($item->file_path && Storage::exists($item->file_path),404);
  return Storage::download($item->file_path);
 }
 public function package(Request $request,ContentItem $item,?string $path=null,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  $settings=$item->settings?:[];$root=$settings['package_root']??null;abort_unless($root,404);
  $relative=$path?:ltrim(str_replace($root.'/','',(string)$item->file_path),'/');
  $relative=str_replace('\\','/',$relative);abort_if(str_starts_with($relative,'/')||preg_match('/(^|\/)\.\.(\/|$)/',$relative),400);
  $full=rtrim($root,'/').'/'.ltrim($relative,'/');abort_unless(Storage::exists($full),404);
  $ext=strtolower(pathinfo($full,PATHINFO_EXTENSION));$mime=match($ext){'html','htm'=>'text/html; charset=UTF-8','js'=>'application/javascript','css'=>'text/css','json'=>'application/json','svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','gif'=>'image/gif','webp'=>'image/webp','mp3'=>'audio/mpeg','mp4'=>'video/mp4','pdf'=>'application/pdf',default=>'application/octet-stream'};
  return response(Storage::get($full),200,['Content-Type'=>$mime,'X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; img-src 'self' data: blob:; media-src 'self' data: blob:;"]);
 }
 private function authorizeItem(Request $request,ContentItem $item,LearningAccessService $access):void{
  $section=$item->section;abort_unless($section&&$item->is_active&&$access->canOpenSection($request->user(),$section),403);
  if($item->available_from&&now()->lt($item->available_from))abort(403,'Материал ещё не открыт.');
  if($item->available_until&&now()->gt($item->available_until))abort(403,'Срок доступа к материалу завершён.');
 }
}
