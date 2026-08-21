<?php
namespace App\Services;

use App\Models\{AppSetting,Program,QuizAssignment,QuizAttempt,User};
use Illuminate\Support\Collection;

class QuizReportingService
{
    public function thresholds(): array
    {
        $values=AppSetting::whereIn('key',['grade_3','grade_4','grade_5'])->pluck('value','key');
        return [
            3=>(float)($values['grade_3']??50),
            4=>(float)($values['grade_4']??70),
            5=>(float)($values['grade_5']??90),
        ];
    }

    public function grade(?float $percent): ?int
    {
        if ($percent===null) return null;
        $t=$this->thresholds();
        if ($percent<$t[3]) return 2;
        if ($percent<$t[4]) return 3;
        if ($percent<$t[5]) return 4;
        return 5;
    }

    public function build(int $programId,string $mode='latest'): array
    {
        $program=Program::findOrFail($programId);
        $assignments=QuizAssignment::with('quiz')->where('program_id',$programId)->orderBy('id')->get();
        $users=User::whereHas('enrollments',fn($q)=>$q->where('program_id',$programId))->orderBy('full_name')->get();
        $attempts=QuizAttempt::whereIn('quiz_assignment_id',$assignments->pluck('id'))
            ->whereIn('user_id',$users->pluck('id'))->orderBy('finished_at')->orderBy('id')->get()
            ->groupBy(fn($a)=>$a->user_id.':'.$a->quiz_assignment_id);

        $rows=$users->map(function(User $user) use($assignments,$attempts,$mode){
            $cells=[];$percents=[];$passed=0;$failed=0;$missing=0;
            foreach($assignments as $assignment){
                $set=$attempts->get($user->id.':'.$assignment->id,collect());
                $chosen=$this->choose($set,$mode);
                if($chosen){
                    $percent=(float)$chosen->percent;$percents[]=$percent;
                    if($chosen->passed)$passed++;else $failed++;
                }else{$percent=null;$missing++;}
                $cells[$assignment->id]=[
                    'attempt'=>$chosen,
                    'percent'=>$percent,
                    'grade'=>$this->grade($percent),
                    'attempts'=>$set->count(),
                ];
            }
            $average=count($percents)?(float)ceil(array_sum($percents)/count($percents)):null;
            return [
                'user'=>$user,'cells'=>$cells,'average'=>$average,'grade'=>$this->grade($average),
                'passed'=>$passed,'failed'=>$failed,'missing'=>$missing,
            ];
        });

        $summary=[
            'students'=>$rows->count(),
            'passed_cells'=>$rows->sum('passed'),
            'failed_cells'=>$rows->sum('failed'),
            'missing_cells'=>$rows->sum('missing'),
            'fully_completed'=>$rows->filter(fn($r)=>$r['missing']===0&&$r['failed']===0)->count(),
        ];

        return compact('program','assignments','rows','summary','mode')+['thresholds'=>$this->thresholds()];
    }

    public function student(int $programId,int $userId,string $mode='latest'): array
    {
        $data=$this->build($programId,$mode);
        $row=$data['rows']->first(fn($r)=>$r['user']->id===$userId);
        abort_unless($row,404);
        return $data+compact('row');
    }

    private function choose(Collection $set,string $mode): ?QuizAttempt
    {
        if($set->isEmpty()) return null;
        if($mode==='best') return $set->sortByDesc(fn($a)=>[(float)$a->percent,$a->finished_at?->timestamp??0,$a->id])->first();
        return $set->sortByDesc(fn($a)=>[$a->finished_at?->timestamp??0,$a->id])->first();
    }
}
