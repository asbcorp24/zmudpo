<?php
namespace App\Http\Controllers;
use App\Models\{PracticeAssignment,Submission};
use App\Services\LearningAccessService;
use Illuminate\Http\Request;
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
  $path=$request->file('file')?->store('submissions');
  Submission::updateOrCreate(['practice_assignment_id'=>$assignment->id,'user_id'=>$request->user()->id],['file_path'=>$path,'comment'=>$data['comment']??null,'status'=>'submitted','submitted_at'=>now()]);
  return back()->with('success','Работа отправлена куратору.');
 }
}
