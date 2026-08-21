<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,LearningSection,SectionProgress,QuizAssignment,QuizAttempt,PracticeAssignment,Submission,EducationDocument,FinalWork,FinalWorkDefinition};
use App\Services\{LearningAccessService,ProgramProgressService,CompletionService};
use Illuminate\Http\Request;

class StudentProgramController extends Controller
{
    public function show(Request $request, Enrollment $enrollment, LearningAccessService $access, ProgramProgressService $progress)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);
        if($enrollment->status==='active') $access->enrollment($request->user(), $enrollment->program_id);
        $enrollment->load(['program','group','curator']);
        $progress->calculate($enrollment);

        $sections=LearningSection::where('program_id',$enrollment->program_id)->where('is_active',true)->orderBy('position')->get();
        $sectionProgress=SectionProgress::where('user_id',$request->user()->id)->whereIn('learning_section_id',$sections->pluck('id'))->get()->keyBy('learning_section_id');
        foreach($sections as $section)$section->setAttribute('can_open',$enrollment->status==='active' && $access->canOpenSection($request->user(),$section));

        $quizzes=QuizAssignment::with('quiz')->where('program_id',$enrollment->program_id)->where('is_active',true)->orderBy('id')->get();
        $quizAttempts=QuizAttempt::where('user_id',$request->user()->id)->whereIn('quiz_assignment_id',$quizzes->pluck('id'))->latest('finished_at')->get()->groupBy('quiz_assignment_id');

        $practice=PracticeAssignment::where('program_id',$enrollment->program_id)->where('is_active',true)->orderBy('ends_at')->get();
        $submissions=Submission::where('user_id',$request->user()->id)->whereIn('practice_assignment_id',$practice->pluck('id'))->get()->keyBy('practice_assignment_id');

        $documents=EducationDocument::where('user_id',$request->user()->id)->where('program_id',$enrollment->program_id)->latest('issued_at')->get();
        $finalDefinitions=FinalWorkDefinition::with(['themes'=>fn($q)=>$q->where('is_active',true)])->where('is_active',true)->whereHas('programs',fn($q)=>$q->where('programs.id',$enrollment->program_id))->get();
        $finalWorks=FinalWork::with(['definition','theme'])->where('enrollment_id',$enrollment->id)->latest()->get();

        return view('programs.show',compact('enrollment','sections','sectionProgress','quizzes','quizAttempts','practice','submissions','documents','finalDefinitions','finalWorks'));
    }

    public function complete(Request $request, Enrollment $enrollment, CompletionService $completion)
    {
        abort_unless($enrollment->user_id===$request->user()->id,403);
        abort_unless($enrollment->status==='active',422,'Программа уже закрыта или недоступна.');
        $enrollment->load('program');
        $completion->complete($enrollment);
        return redirect()->route('programs.show',$enrollment)->with('ok','Программа успешно завершена.');
    }
}
