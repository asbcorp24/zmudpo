<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Quiz;
use App\Models\User;
class DashboardController extends Controller { public function __invoke(){return view('admin.dashboard',['users'=>User::count(),'students'=>User::where('role','student')->count(),'programs'=>Program::count(),'quizzes'=>Quiz::count()]);} }
