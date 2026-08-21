<?php
namespace App\Services;
use App\Models\{Enrollment,LearningSection,SectionProgress,PracticeAssignment,Submission,FinalWork,FinalWorkDefinition,QuizAssignment,QuizAttempt};
class ProgramProgressService {
 public function calculate(Enrollment $e): float {
  $requiredSections=LearningSection::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $sectionPct=$requiredSections->isEmpty()?100:(SectionProgress::where('user_id',$e->user_id)->whereIn('learning_section_id',$requiredSections)->sum('progress_percent')/$requiredSections->count());

  $practiceIds=PracticeAssignment::where('program_id',$e->program_id)->where('is_active',1)->pluck('id');
  $practicePct=$practiceIds->isEmpty()?100:(Submission::where('user_id',$e->user_id)->whereIn('practice_assignment_id',$practiceIds)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id')/$practiceIds->count()*100);

  $quizIds=QuizAssignment::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $quizPct=$quizIds->isEmpty()?100:(QuizAttempt::where('user_id',$e->user_id)->whereIn('quiz_assignment_id',$quizIds)->where('passed',1)->distinct('quiz_assignment_id')->count('quiz_assignment_id')/$quizIds->count()*100);

  $finalDefinitionIds=FinalWorkDefinition::where('is_active',1)->whereHas('programs',fn($q)=>$q->where('programs.id',$e->program_id))->pluck('id');
  if($finalDefinitionIds->isNotEmpty()) {
   $acceptedFinals=FinalWork::where('enrollment_id',$e->id)->whereIn('final_work_definition_id',$finalDefinitionIds)->whereIn('status',['accepted','passed'])->distinct('final_work_definition_id')->count('final_work_definition_id');
   $finalPct=$acceptedFinals/$finalDefinitionIds->count()*100;
   $hasFinalRequirement=true;
  } else {
   $hasFinalRequirement=FinalWork::where('enrollment_id',$e->id)->exists();
   $finalPct=$hasFinalRequirement?(FinalWork::where('enrollment_id',$e->id)->whereIn('status',['accepted','passed'])->exists()?100:0):100;
  }

  $parts=collect([$sectionPct]);
  if($practiceIds->isNotEmpty())$parts->push($practicePct);
  if($quizIds->isNotEmpty())$parts->push($quizPct);
  if($hasFinalRequirement)$parts->push($finalPct);
  $pct=round((float)$parts->avg(),2);
  $e->update(['progress_percent'=>$pct]);
  return $pct;
 }
}
