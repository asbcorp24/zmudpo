<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AttendanceRecord,Enrollment,Group,Program,QuizAttempt,Submission};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        return view('admin.reports.index',[
            'programs'=>Program::orderBy('title')->get(),
            'groups'=>Group::orderBy('name')->get(),
        ]);
    }

    private function enrollments(Request $r)
    {
        $q=Enrollment::with(['user','program','group'])->whereIn('status',['active','completed']);
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        if($r->filled('group_id'))$q->where('group_id',$r->integer('group_id'));
        return $q->orderBy('group_id')->get();
    }

    public function attendance(Request $r)
    {
        $enrollments=$this->enrollments($r);
        $records=AttendanceRecord::whereIn('enrollment_id',$enrollments->pluck('id'))
            ->when($r->filled('date_from'),fn($q)=>$q->whereDate('attendance_date','>=',$r->date('date_from')))
            ->when($r->filled('date_to'),fn($q)=>$q->whereDate('attendance_date','<=',$r->date('date_to')))
            ->orderBy('attendance_date')->get();
        $dates=$records->pluck('attendance_date')->map->format('Y-m-d')->unique()->values();
        $matrix=$records->keyBy(fn($x)=>$x->enrollment_id.'-'.$x->attendance_date->format('Y-m-d'));
        return view('admin.reports.attendance',compact('enrollments','records','dates','matrix'));
    }

    public function achievement(Request $r)
    {
        $enrollments=$this->enrollments($r);
        $userIds=$enrollments->pluck('user_id');
        $programIds=$enrollments->pluck('program_id');

        $quiz=QuizAttempt::whereIn('user_id',$userIds)
            ->whereHas('assignment',fn($q)=>$q->whereIn('program_id',$programIds))
            ->selectRaw('user_id,max(percent) best_percent,sum(case when passed=1 then 1 else 0 end) passed_tests,count(*) attempts')
            ->groupBy('user_id')->get()->keyBy('user_id');

        $practice=Submission::whereIn('user_id',$userIds)
            ->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$programIds))
            ->selectRaw("user_id,count(*) total,sum(case when status in ('accepted','passed') then 1 else 0 end) accepted,avg(score) avg_score")
            ->groupBy('user_id')->get()->keyBy('user_id');

        return view('admin.reports.achievement',compact('enrollments','quiz','practice'));
    }
}
