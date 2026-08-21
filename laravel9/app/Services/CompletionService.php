<?php
namespace App\Services;
use App\Models\{Enrollment,LearningSection,SectionProgress,PracticeAssignment,Submission,FinalWork,FinalWorkDefinition,ArchiveRecord,QuizAssignment,QuizAttempt};
use Illuminate\Validation\ValidationException;
class CompletionService {
 public function complete(Enrollment $e):void{
  $missingSections=LearningSection::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->whereNotIn('id',SectionProgress::where('user_id',$e->user_id)->whereNotNull('completed_at')->pluck('learning_section_id'))->count();
  $practice=PracticeAssignment::where('program_id',$e->program_id)->where('is_active',1)->pluck('id');
  $accepted=Submission::where('user_id',$e->user_id)->whereIn('practice_assignment_id',$practice)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id');
  $requiredAssignments=QuizAssignment::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $passedAssignments=QuizAttempt::where('user_id',$e->user_id)->whereIn('quiz_assignment_id',$requiredAssignments)->where('passed',1)->distinct('quiz_assignment_id')->count('quiz_assignment_id');

  $finalDefinitionIds=FinalWorkDefinition::where('is_active',1)->whereHas('programs',fn($q)=>$q->where('programs.id',$e->program_id))->pluck('id');
  $missingFinals=0;
  if($finalDefinitionIds->isNotEmpty()) {
   $acceptedFinals=FinalWork::where('enrollment_id',$e->id)->whereIn('final_work_definition_id',$finalDefinitionIds)->whereIn('status',['accepted','passed'])->distinct('final_work_definition_id')->count('final_work_definition_id');
   $missingFinals=max(0,$finalDefinitionIds->count()-$acceptedFinals);
  } else {
   $legacyFinal=FinalWork::where('enrollment_id',$e->id)->latest()->first();
   if($legacyFinal&&!in_array($legacyFinal->status,['accepted','passed'],true))$missingFinals=1;
  }

  $errors=[];
  if($missingSections)$errors[]="Не завершено обязательных разделов: $missingSections";
  if($accepted<$practice->count())$errors[]='Не приняты все практические работы';
  if($passedAssignments<$requiredAssignments->count())$errors[]='Не пройдены все обязательные тесты';
  if($missingFinals)$errors[]="Не приняты итоговые работы: $missingFinals";
  if($errors)throw ValidationException::withMessages(['completion'=>$errors]);

  if($e->status!=='completed') {
   $e->update(['status'=>'completed','progress_percent'=>100,'completed_at'=>now()]);
   ArchiveRecord::firstOrCreate(
    ['user_id'=>$e->user_id,'program_id'=>$e->program_id,'type'=>'completion','data->enrollment_id'=>$e->id],
    ['title'=>$e->program->title,'started_at'=>$e->started_at,'ended_at'=>now(),'data'=>['enrollment_id'=>$e->id,'required_quiz_assignments'=>$requiredAssignments->count(),'required_final_works'=>$finalDefinitionIds->count()]]
   );
  }
 }
}
