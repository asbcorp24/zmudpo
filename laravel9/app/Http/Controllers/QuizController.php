<?php
namespace App\Http\Controllers;
use App\Models\{QuizAssignment,QuizAttempt,QuizAttemptDetail,Enrollment};
use App\Services\{LearningAccessService,QuizAccessService};
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $r, QuizAccessService $rules)
    {
        $programIds=Enrollment::where('user_id',$r->user()->id)->where('status','active')->pluck('program_id');
        $assignments=QuizAssignment::with('quiz')->whereIn('program_id',$programIds)->orderBy('program_id')->orderBy('title')->get();
        $latest=QuizAttempt::where('user_id',$r->user()->id)->whereIn('quiz_assignment_id',$assignments->pluck('id'))->latest('finished_at')->get()->unique('quiz_assignment_id')->keyBy('quiz_assignment_id');
        foreach($assignments as $a){$a->access_status=$rules->status($a,$r->user());$a->last_attempt=$latest->get($a->id);}
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
        $answers=$r->input('answers',[]);$score=0;$max=0;$details=[];
        foreach($quiz->questions as $q){
            $max+=(float)$q->points;$selected=(array)($answers[$q->id]??[]);
            $correct=$q->options->where('is_correct',true)->pluck('id')->map(fn($v)=>(string)$v)->sort()->values()->all();
            $given=collect($selected)->map(fn($v)=>(string)$v)->sort()->values()->all();$isCorrect=$given===$correct;
            if($isCorrect)$score+=(float)$q->points;
            $details[]=[
                'question_id'=>$q->id,'legacy_id'=>$q->legacy_id,'question'=>$q->question,'correct'=>$isCorrect,
                'selected_ids'=>$given,'correct_ids'=>$correct,
                'selected_answers'=>$q->options->whereIn('id',$selected)->pluck('text')->values()->all(),
                'correct_answers'=>$q->options->where('is_correct',true)->pluck('text')->values()->all(),
                'points'=>(float)$q->points,'earned'=>$isCorrect?(float)$q->points:0,
            ];
        }
        $percent=$max>0?round($score/$max*100,2):0;
        $a=QuizAttempt::create(['quiz_id'=>$quiz->id,'quiz_assignment_id'=>$assignment->id,'user_id'=>$r->user()->id,'score'=>$score,'percent'=>$percent,'passed'=>$percent>=$assignment->pass_percent,'answers'=>$answers,'started_at'=>now(),'finished_at'=>now(),'ip'=>$r->ip()]);
        QuizAttemptDetail::create(['quiz_attempt_id'=>$a->id,'source'=>'laravel','questions'=>$details,'extra'=>['max_score'=>$max,'pass_percent'=>$assignment->pass_percent]]);
        return redirect()->route('quizzes.result',$a)->with('ok',($a->passed?'Тест пройден: ':'Тест не пройден: ').$percent.'%');
    }
    public function result(Request $r,QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id===$r->user()->id,403);
        $attempt->load('quiz','assignment.program');
        $detail=QuizAttemptDetail::where('quiz_attempt_id',$attempt->id)->first();
        return view('quizzes.result',compact('attempt','detail'));
    }
    private function allow(Request $r, QuizAssignment $a, LearningAccessService $access, QuizAccessService $rules): void
    {
        $access->enrollment($r->user(),$a->program_id);if($a->learning_section_id){$s=$a->learningSection;if($s&&!$access->canOpenSection($r->user(),$s))abort(403,'Предыдущий раздел ещё не завершён.');}$rules->assertAvailable($a,$r->user());
    }
}
