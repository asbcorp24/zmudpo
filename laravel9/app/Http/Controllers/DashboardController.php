<?php
namespace App\Http\Controllers;
use App\Models\Announcement;
use App\Models\QuizAttempt;
use App\Models\SectionProgress;
use Illuminate\Http\Request;
class DashboardController extends Controller { public function __invoke(Request $request){$user=$request->user();$enrollments=$user->enrollments()->with(['program','group'])->get();$programIds=$enrollments->pluck('program_id');$announcements=Announcement::where('is_active',true)->where(function($q)use($programIds){$q->whereNull('program_id')->orWhereIn('program_id',$programIds);})->latest('published_at')->limit(5)->get();$avgProgress=SectionProgress::where('user_id',$user->id)->avg('progress_percent')??0;$lastAttempts=QuizAttempt::where('user_id',$user->id)->latest()->limit(5)->get();return view('dashboard',compact('user','enrollments','announcements','avgProgress','lastAttempts'));} }
