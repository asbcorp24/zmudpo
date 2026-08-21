<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Enrollment,Submission,FinalWork};
use App\Services\ProgramProgressService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user=$request->user();
        $submissions=Submission::with('user','practiceAssignment')->where('status','submitted');
        $finalWorks=FinalWork::with('user','enrollment.program')->where('status','submitted');
        if(!$user->isAdmin()){
            $enrollments=Enrollment::where('curator_id',$user->id)->get(['user_id','program_id','id']);
            $studentIds=$enrollments->pluck('user_id');
            $programIds=$enrollments->pluck('program_id');
            $enrollmentIds=$enrollments->pluck('id');
            $submissions->whereIn('user_id',$studentIds)->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$programIds));
            $finalWorks->whereIn('enrollment_id',$enrollmentIds);
        }
        return view('admin.review.index',['submissions'=>$submissions->latest()->get(),'finalWorks'=>$finalWorks->latest()->get()]);
    }

    public function submission(Request $r,Submission $submission,ProgramProgressService $progress)
    {
        $enrollment=Enrollment::where('user_id',$submission->user_id)->where('program_id',$submission->practiceAssignment->program_id)->firstOrFail();
        abort_unless($r->user()->isAdmin() || $enrollment->curator_id===$r->user()->id,403);
        $d=$r->validate(['status'=>'required|in:accepted,rejected,revision','score'=>'nullable|numeric','review_comment'=>'nullable|string']);
        $submission->update($d+['reviewed_by'=>$r->user()->id,'reviewed_at'=>now()]);
        $progress->calculate($enrollment);
        return back()->with('ok','Практическая работа проверена');
    }

    public function finalWork(Request $r,FinalWork $finalWork,ProgramProgressService $progress)
    {
        $finalWork->loadMissing('enrollment');
        abort_unless($r->user()->isAdmin() || $finalWork->enrollment?->curator_id===$r->user()->id,403);
        $d=$r->validate(['status'=>'required|in:accepted,rejected,revision','score'=>'nullable|numeric','antiplagiarism_percent'=>'nullable|integer|min:0|max:100','review_comment'=>'nullable|string']);
        $finalWork->update($d+['reviewed_by'=>$r->user()->id,'reviewed_at'=>now()]);
        $progress->calculate($finalWork->enrollment);
        return back()->with('ok','Итоговая работа проверена');
    }
}
