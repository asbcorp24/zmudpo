<?php
namespace App\Http\Controllers\Curator;
use App\Http\Controllers\Controller;
use App\Models\{Enrollment,Submission,User};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request){return $this->index($request);}

    public function index(Request $request)
    {
        $user=$request->user();
        $enrollmentQuery=Enrollment::query();
        if(!$user->isAdmin())$enrollmentQuery->where('curator_id',$user->id);
        $enrollmentIds=(clone $enrollmentQuery)->pluck('id');
        $studentIds=(clone $enrollmentQuery)->pluck('user_id');
        $programIds=(clone $enrollmentQuery)->pluck('program_id');
        $submissionQuery=Submission::whereIn('user_id',$studentIds)->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$programIds));
        return view('curator.dashboard',[
            'students'=>User::whereIn('id',$studentIds)->where('role','student')->where('is_active',true)->count(),
            'activeEnrollments'=>(clone $enrollmentQuery)->where('status','active')->count(),
            'pendingSubmissions'=>(clone $submissionQuery)->where('status','submitted')->count(),
            'recentSubmissions'=>(clone $submissionQuery)->with('user')->latest('submitted_at')->limit(10)->get(),
        ]);
    }
}
