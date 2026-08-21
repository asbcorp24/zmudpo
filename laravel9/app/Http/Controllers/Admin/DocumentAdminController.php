<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{EducationDocument,Enrollment,User,Program}; use Illuminate\Http\Request;
class DocumentAdminController extends Controller {
 public function index(Request $r){$q=EducationDocument::query();if($r->filled('user_id'))$q->where('user_id',$r->user_id);return view('admin.documents.index',['items'=>$q->latest()->paginate(40),'completed'=>Enrollment::with('user','program')->where('status','completed')->latest('completed_at')->limit(200)->get()]);}
 public function store(Request $r){$d=$r->validate(['user_id'=>'required|exists:users,id','program_id'=>'required|exists:programs,id','type'=>'required|max:50','title'=>'required|max:255','number'=>'nullable|max:100','issued_at'=>'nullable|date','file_path'=>'nullable|max:255']);$ok=Enrollment::where('user_id',$d['user_id'])->where('program_id',$d['program_id'])->where('status','completed')->exists();if(!$ok)return back()->withErrors(['document'=>'Документ можно выдать только после завершения программы.']);EducationDocument::create($d+['issued_at'=>$d['issued_at']??now()->toDateString()]);return back()->with('ok','Документ выдан');}
 public function destroy(EducationDocument $document){$document->delete();return back()->with('ok','Документ отозван');}
}