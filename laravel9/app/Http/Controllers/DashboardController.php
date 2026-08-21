<?php
namespace App\Http\Controllers;
use App\Models\{Announcement,QuizAttempt};
use App\Services\ProgramProgressService;
use Illuminate\Http\Request;
class DashboardController extends Controller {
 public function __invoke(Request $request,ProgramProgressService $progress){
  $user=$request->user();
  $enrollments=$user->enrollments()->with(['program','group'])->latest()->get();
  foreach($enrollments as $e){if(in_array($e->status,['active','completed'],true))$e->progress_percent=$progress->calculate($e);}
  $programIds=$enrollments->pluck('program_id');
  $announcements=Announcement::where('is_active',true)->where(function($q)use($programIds){$q->whereNull('program_id')->orWhereIn('program_id',$programIds);})->latest('published_at')->limit(5)->get();
  $active=$enrollments->where('status','active');
  $avgProgress=$active->isEmpty()?0:round((float)$active->avg(fn($e)=>(float)$e->progress_percent),2);
  $lastAttempts=QuizAttempt::with('assignment')->where('user_id',$user->id)->latest('finished_at')->limit(5)->get();
  return view('dashboard',compact('user','enrollments','announcements','avgProgress','lastAttempts'));
 }
 public function index(Request $request,ProgramProgressService $progress){return $this->__invoke($request,$progress);}
}
