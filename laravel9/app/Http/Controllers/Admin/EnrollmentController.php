<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{Enrollment,User,Program,Group}; use App\Services\{ProgramProgressService,CompletionService}; use Illuminate\Http\Request;
class EnrollmentController extends Controller {
 public function index(Request $r,ProgramProgressService $progress){$q=Enrollment::with('user','program','group','curator');if($r->filled('status'))$q->where('status',$r->status);if($r->filled('program_id'))$q->where('program_id',$r->program_id);$items=$q->latest()->paginate(40)->withQueryString();foreach($items as $e)if($e->status==='active')$progress->calculate($e);return view('admin.enrollments.index',compact('items'));}
 public function update(Request $r,Enrollment $enrollment){$enrollment->update($r->validate(['group_id'=>'nullable|exists:groups,id','curator_id'=>'nullable|exists:users,id','status'=>'required|in:active,blocked,completed,archived','started_at'=>'nullable|date','ends_at'=>'nullable|date','admin_comment'=>'nullable|string']));return back()->with('ok','Доступ обновлён');}
 public function complete(Enrollment $enrollment,CompletionService $service){$service->complete($enrollment->load('program'));return back()->with('ok','Обучение завершено и перенесено в архив');}
}