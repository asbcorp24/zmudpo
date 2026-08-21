<?php
namespace App\Services;
use App\Models\{Enrollment,LearningSection,SectionProgress,PracticeAssignment,Submission,FinalWork,ArchiveRecord,QuizAssignment,QuizAttempt};
use Illuminate\Validation\ValidationException;
class CompletionService {
 public function complete(Enrollment $e):void{
  $missingSections=LearningSection::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->whereNotIn('id',SectionProgress::where('user_id',$e->user_id)->whereNotNull('completed_at')->pluck('learning_section_id'))->count();
  $practice=PracticeAssignment::where('program_id',$e->program_id)->where('is_active',1)->pluck('id');
  $accepted=Submission::where('user_id',$e->user_id)->whereIn('practice_assignment_id',$practice)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id');
  $requiredAssignments=QuizAssignment::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $passedAssignments=QuizAttempt::where('user_id',$e->user_id)->whereIn('quiz_assignment_id',$requiredAssignments)->where('passed',1)->distinct('quiz_assignment_id')->count('quiz_assignment_id');
  $final=FinalWork::where('enrollment_id',$e->id)->latest()->first();
  $errors=[];
  if($missingSections)$errors[]="Не завершено обязательных разделов: $missingSections";
  if($accepted<$practice->count())$errors[]='Не приняты все практические работы';
  if($passedAssignments<$requiredAssignments->count())$errors[]='Не пройдены все обязательные тесты';
  if($final&&!in_array($final->status,['accepted','passed']))$errors[]='Итоговая работа не принята';
  if($errors)throw ValidationException::withMessages(['completion'=>$errors]);
  $e->update(['status'=>'completed','progress_percent'=>100,'completed_at'=>now()]);
  ArchiveRecord::create(['user_id'=>$e->user_id,'program_id'=>$e->program_id,'type'=>'completion','title'=>$e->program->title,'started_at'=>$e->started_at,'ended_at'=>now(),'data'=>['enrollment_id'=>$e->id,'required_quiz_assignments'=>$requiredAssignments->count()]]);
 }
}
