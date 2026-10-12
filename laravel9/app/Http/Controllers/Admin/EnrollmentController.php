<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{Enrollment,User,Program,Group}; use App\Services\{ProgramProgressService,CompletionService}; use Illuminate\Http\Request;
class EnrollmentController extends Controller {
 public function index(Request $r,ProgramProgressService $progress){$q=Enrollment::with('user','program','group','curator');if($r->filled('status'))$q->where('status',$r->status);if($r->filled('program_id'))$q->where('program_id',$r->program_id);if($r->filled('group_id'))$q->where('group_id',$r->group_id);$items=$q->latest()->paginate(60)->withQueryString();foreach($items as $e)if($e->status==='active')$progress->calculate($e);return view('admin.enrollments.index',['items'=>$items,'programs'=>Program::orderBy('title')->get(),'groups'=>Group::orderBy('name')->get(),'curators'=>User::whereIn('role',['curator','admin'])->where('is_active',1)->orderBy('full_name')->get()]);}
 public function update(Request $r,Enrollment $enrollment){$enrollment->update($r->validate(['group_id'=>'nullable|exists:groups,id','curator_id'=>'nullable|exists:users,id','status'=>'required|in:active,blocked,completed,archived','started_at'=>'nullable|date','ends_at'=>'nullable|date','admin_comment'=>'nullable|string']));return back()->with('ok','Доступ обновлён');}
 public function bulk(Request $r){
  $d=$r->validate(['ids'=>'required|array|min:1','ids.*'=>'exists:enrollments,id','group_id'=>'nullable|exists:groups,id','curator_id'=>'nullable|exists:users,id','status'=>'nullable|in:active,blocked,completed,archived','started_at'=>'nullable|date','ends_at'=>'nullable|date']);
  $update=[];foreach(['group_id','curator_id','status','started_at','ends_at'] as $key)if($r->has($key)&&$r->input($key)!=='')$update[$key]=$d[$key]??null;
  if($update)Enrollment::whereIn('id',$d['ids'])->update($update);
  return back()->with('ok','Массовые изменения сохранены: '.count($d['ids']));
 }
 public function complete(Enrollment $enrollment,CompletionService $service){$service->complete($enrollment->load('program'));return back()->with('ok','Обучение завершено и перенесено в архив');}
}