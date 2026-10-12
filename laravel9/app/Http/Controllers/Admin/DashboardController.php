<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User,Program,Enrollment,EducationDocument};

class DashboardController extends Controller
{
 public function index()
 {
  return view('admin.dashboard',[
   'users'=>User::count(),
   'programs'=>Program::where('is_active',1)->count(),
   'active'=>Enrollment::where('status','active')->count(),
   'completed'=>Enrollment::where('status','completed')->count(),
   'documents'=>EducationDocument::count(),
  ]);
 }
}