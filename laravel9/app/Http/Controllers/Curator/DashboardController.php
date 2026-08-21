<?php
namespace App\Http\Controllers\Curator;
use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\User;
class DashboardController extends Controller { public function __invoke(){return view('curator.dashboard',['students'=>User::where('role','student')->where('is_active',true)->count(),'activeEnrollments'=>Enrollment::where('status','active')->count(),'pendingSubmissions'=>Submission::where('status','submitted')->count(),'recentSubmissions'=>Submission::with('user')->latest('submitted_at')->limit(10)->get()]);} }
