<?php
namespace App\Services;
use App\Models\{Enrollment,LearningSection,SectionProgress,PracticeAssignment,Submission,FinalWork};
class ProgramProgressService {
 public function calculate(Enrollment $e): float {
  $required=LearningSection::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $sectionPct=$required->isEmpty()?100:(SectionProgress::where('user_id',$e->user_id)->whereIn('learning_section_id',$required)->sum('progress_percent')/$required->count());
  $practiceIds=PracticeAssignment::where('program_id',$e->program_id)->where('is_active',1)->pluck('id');
  $practicePct=$practiceIds->isEmpty()?100:(Submission::where('user_id',$e->user_id)->whereIn('practice_assignment_id',$practiceIds)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id')/$practiceIds->count()*100);
  $hasFinal=FinalWork::where('enrollment_id',$e->id)->exists(); $finalPct=$hasFinal?(FinalWork::where('enrollment_id',$e->id)->whereIn('status',['accepted','passed'])->exists()?100:0):100;
  $parts=collect([$sectionPct]); if($practiceIds->isNotEmpty())$parts->push($practicePct); if($hasFinal)$parts->push($finalPct); $pct=round($parts->avg(),2); $e->update(['progress_percent'=>$pct]); return $pct;
 }
}