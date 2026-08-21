<?php
namespace App\Http\Controllers;
use App\Models\{PracticeAssignment,Submission};
use App\Services\LearningAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class PracticeController extends Controller {
 public function index(Request $request){
  $programIds=$request->user()->enrollments()->where('status','active')->pluck('program_id');
  $assignments=PracticeAssignment::with('program')->whereIn('program_id',$programIds)->where('is_active',true)->orderBy('ends_at')->get();
  $submissions=Submission::where('user_id',$request->user()->id)->whereIn('practice_assignment_id',$assignments->pluck('id'))->get()->keyBy('practice_assignment_id');
  return view('practice.index',compact('assignments','submissions'));
 }
 public function submit(Request $request,PracticeAssignment $assignment,LearningAccessService $access){
  $access->enrollment($request->user(),$assignment->program_id);
  abort_unless($assignment->is_active,403,'Задание отключено.');
  if($assignment->starts_at && now()->lt($assignment->starts_at->startOfDay()))abort(403,'Задание ещё не открыто.');
  if($assignment->ends_at && now()->gt($assignment->ends_at->endOfDay()))abort(403,'Срок отправки работы завершён.');
  $data=$request->validate(['file'=>['nullable','file','max:20480'],'comment'=>['nullable','string','max:5000']]);
  $submission=Submission::firstOrNew(['practice_assignment_id'=>$assignment->id,'user_id'=>$request->user()->id]);
  if($request->hasFile('file')){
   $newPath=$request->file('file')->store('submissions/'.$request->user()->id);
   if($submission->file_path)Storage::delete($submission->file_path);
   $submission->file_path=$newPath;
  }
  $submission->comment=$data['comment']??null;
  $submission->status='submitted';
  $submission->submitted_at=now();
  $submission->reviewed_by=null;$submission->reviewed_at=null;$submission->review_comment=null;
  $submission->save();
  return back()->with('success','Работа отправлена куратору.');
 }
 public function download(Request $request,Submission $submission){
  $allowed=$submission->user_id===$request->user()->id || in_array($request->user()->role,['curator','admin'],true);
  abort_unless($allowed,403);
  abort_unless($submission->file_path && Storage::exists($submission->file_path),404);
  $ext=pathinfo($submission->file_path,PATHINFO_EXTENSION);
  $name=($submission->practiceAssignment?->title?:'practice-work').($ext?'.'.$ext:'');
  return Storage::download($submission->file_path,$name);
 }
}
