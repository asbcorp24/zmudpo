<?php
namespace App\Http\Controllers;
use App\Models\{LearningSection,SectionProgress,QuizAssignment,PracticeAssignment,QuizAttempt,Submission};
use App\Services\LearningAccessService;
use Illuminate\Http\Request;
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
  $section->load('program','contentItems');
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
}
