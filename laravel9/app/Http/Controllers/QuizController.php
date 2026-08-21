<?php
namespace App\Http\Controllers;
use App\Models\{QuizAssignment,QuizAttempt,Enrollment};
use App\Services\{LearningAccessService,QuizAccessService};
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $r, QuizAccessService $rules)
    {
        $programIds=Enrollment::where('user_id',$r->user()->id)->where('status','active')->pluck('program_id');
        $assignments=QuizAssignment::with('quiz')->whereIn('program_id',$programIds)->orderBy('program_id')->orderBy('title')->get();
        foreach($assignments as $a)$a->access_status=$rules->status($a,$r->user());
        return view('quizzes.index',compact('assignments'));
    }
    public function show(Request $r, QuizAssignment $assignment, LearningAccessService $access, QuizAccessService $rules)
    {
        $this->allow($r,$assignment,$access,$rules);$assignment->load('quiz.questions.options');$quiz=$assignment->quiz;
        return view('quizzes.show',compact('quiz','assignment'));
    }
    public function submit(Request $r, QuizAssignment $assignment, LearningAccessService $access, QuizAccessService $rules)
    {
        $this->allow($r,$assignment,$access,$rules);$quiz=$assignment->quiz()->with('questions.options')->firstOrFail();
        $answers=$r->input('answers',[]);$score=0;$max=0;
        foreach($quiz->questions as $q){$max+=(float)$q->points;$selected=(array)($answers[$q->id]??[]);$correct=$q->options->where('is_correct',true)->pluck('id')->map(fn($v)=>(string)$v)->sort()->values()->all();$given=collect($selected)->map(fn($v)=>(string)$v)->sort()->values()->all();if($given===$correct)$score+=(float)$q->points;}
        $percent=$max>0?round($score/$max*100,2):0;
        $a=QuizAttempt::create(['quiz_id'=>$quiz->id,'quiz_assignment_id'=>$assignment->id,'user_id'=>$r->user()->id,'score'=>$score,'percent'=>$percent,'passed'=>$percent>=$assignment->pass_percent,'answers'=>$answers,'started_at'=>now(),'finished_at'=>now(),'ip'=>$r->ip()]);
        return redirect()->route('quizzes.index')->with('ok',($a->passed?'Тест пройден: ':'Тест не пройден: ').$percent.'%');
    }
    private function allow(Request $r, QuizAssignment $a, LearningAccessService $access, QuizAccessService $rules): void
    {
        $access->enrollment($r->user(),$a->program_id);if($a->learning_section_id){$s=$a->learningSection;if($s&&!$access->canOpenSection($r->user(),$s))abort(403,'Предыдущий раздел ещё не завершён.');}$rules->assertAvailable($a,$r->user());
    }
}