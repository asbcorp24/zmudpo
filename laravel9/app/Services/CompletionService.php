<?php
namespace App\Services;

use App\Models\{
 ArchiveRecord,ContentItem,ContentItemResponse,Enrollment,FinalWork,FinalWorkDefinition,
 LearningSection,PracticeAssignment,QuizAssignment,QuizAttempt,SectionProgress,Submission
};
use Illuminate\Validation\ValidationException;

class CompletionService
{
 public function complete(Enrollment $e):void
 {
  $e->loadMissing(['user','program','group','curator']);

  $requiredSections=LearningSection::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $missingSections=$requiredSections->count()-SectionProgress::where('user_id',$e->user_id)->whereIn('learning_section_id',$requiredSections)->whereNotNull('completed_at')->count();

  $practice=PracticeAssignment::where('program_id',$e->program_id)->where('is_active',1)->pluck('id');
  $accepted=Submission::where('user_id',$e->user_id)->whereIn('practice_assignment_id',$practice)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id');

  $requiredAssignments=QuizAssignment::where('program_id',$e->program_id)->where('is_active',1)->where('is_required',1)->pluck('id');
  $passedAssignments=QuizAttempt::where('user_id',$e->user_id)->whereIn('quiz_assignment_id',$requiredAssignments)->where('passed',1)->distinct('quiz_assignment_id')->count('quiz_assignment_id');

  $requiredContent=ContentItem::whereHas('section',fn($q)=>$q->where('program_id',$e->program_id)->where('is_active',1))
   ->where('is_active',1)->where('is_required',1)->whereIn('legacy_type',[4,7,10,17])->pluck('id');
  $acceptedContent=ContentItemResponse::where('user_id',$e->user_id)->whereIn('content_item_id',$requiredContent)->where('status','accepted')->count();

  $finalDefinitionIds=FinalWorkDefinition::where('is_active',1)->whereHas('programs',fn($q)=>$q->where('programs.id',$e->program_id))->pluck('id');
  $missingFinals=0;
  if($finalDefinitionIds->isNotEmpty()){
   $acceptedFinals=FinalWork::where('enrollment_id',$e->id)->whereIn('final_work_definition_id',$finalDefinitionIds)->whereIn('status',['accepted','passed'])->distinct('final_work_definition_id')->count('final_work_definition_id');
   $missingFinals=max(0,$finalDefinitionIds->count()-$acceptedFinals);
  }else{
   $legacyFinal=FinalWork::where('enrollment_id',$e->id)->latest()->first();
   if($legacyFinal&&!in_array($legacyFinal->status,['accepted','passed'],true))$missingFinals=1;
  }

  $errors=[];
  if($missingSections>0)$errors[]="Не завершено обязательных разделов: {$missingSections}";
  if($accepted<$practice->count())$errors[]='Не приняты все практические работы';
  if($passedAssignments<$requiredAssignments->count())$errors[]='Не пройдены все обязательные тесты';
  if($acceptedContent<$requiredContent->count())$errors[]='Не приняты все обязательные контрольные/тетради';
  if($missingFinals)$errors[]="Не приняты итоговые работы: {$missingFinals}";
  if($errors)throw ValidationException::withMessages(['completion'=>$errors]);

  if($e->status==='completed')return;

  $e->update(['status'=>'completed','progress_percent'=>100,'completed_at'=>now()]);

  $alreadyArchived=ArchiveRecord::where('user_id',$e->user_id)->where('program_id',$e->program_id)->where('type','completion')->where('data->enrollment_id',$e->id)->exists();
  if($alreadyArchived)return;

  $quizSummary=QuizAttempt::with('assignment')->where('user_id',$e->user_id)->whereHas('assignment',fn($q)=>$q->where('program_id',$e->program_id))
   ->latest('finished_at')->get()->groupBy('quiz_assignment_id')->map(fn($rows)=>[
      'title'=>$rows->first()?->assignment?->title,
      'best_percent'=>(float)$rows->max('percent'),
      'passed'=>$rows->contains('passed',true),
      'attempts'=>$rows->count(),
   ])->values()->all();

  $practiceSummary=Submission::with('practiceAssignment')->where('user_id',$e->user_id)->whereHas('practiceAssignment',fn($q)=>$q->where('program_id',$e->program_id))->get()->map(fn($x)=>[
      'title'=>$x->practiceAssignment?->title,'status'=>$x->status,'score'=>$x->score,'submitted_at'=>optional($x->submitted_at)->toIso8601String()
   ])->all();

  $contentSummary=ContentItemResponse::with('item.section')->where('user_id',$e->user_id)->whereHas('item.section',fn($q)=>$q->where('program_id',$e->program_id))->get()->map(fn($x)=>[
      'section'=>$x->item?->section?->title,'title'=>$x->item?->title,'legacy_type'=>$x->item?->legacy_type,'status'=>$x->status,'score'=>$x->score
   ])->all();

  $finalSummary=FinalWork::with(['definition','theme'])->where('enrollment_id',$e->id)->get()->map(fn($x)=>[
      'definition'=>$x->definition?->title,'theme'=>$x->theme?->title,'title'=>$x->title,'status'=>$x->status,'score'=>$x->score,'antiplagiarism_percent'=>$x->antiplagiarism_percent
   ])->all();

  ArchiveRecord::create([
   'user_id'=>$e->user_id,
   'program_id'=>$e->program_id,
   'type'=>'completion',
   'title'=>$e->program?->title,
   'started_at'=>$e->started_at,
   'ended_at'=>now(),
   'data'=>[
      'enrollment_id'=>$e->id,
      'user'=>[
        'full_name'=>$e->user?->full_name,'email'=>$e->user?->email,'phone'=>$e->user?->phone,
        'group'=>$e->group?->name,'subgroup'=>$e->user?->subgroup,
      ],
      'program'=>[
        'title'=>$e->program?->title,'hours'=>$e->program?->hours,'mode'=>$e->program?->mode,
      ],
      'curator'=>$e->curator?->full_name,
      'required_sections'=>$requiredSections->count(),
      'quizzes'=>$quizSummary,
      'practice'=>$practiceSummary,
      'content_responses'=>$contentSummary,
      'final_works'=>$finalSummary,
   ]
  ]);
 }
}
