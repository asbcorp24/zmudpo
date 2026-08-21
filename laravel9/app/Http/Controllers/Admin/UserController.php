<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{User,Program,Group,Enrollment}; use Illuminate\Http\Request; use Illuminate\Support\Facades\Hash;
class UserController extends Controller {
 public function index(Request $r){$q=User::query(); if($r->filled('q'))$q->where(fn($x)=>$x->where('full_name','like','%'.$r->q.'%')->orWhere('login','like','%'.$r->q.'%')); if($r->filled('role'))$q->where('role',$r->role); return view('admin.users.index',['items'=>$q->latest()->paginate(40)->withQueryString()]);}
 public function create(){return view('admin.users.form',['item'=>new User,'programs'=>Program::orderBy('title')->get(),'groups'=>Group::orderBy('name')->get()]);}
 public function store(Request $r){$d=$this->data($r,true);$d['password']=Hash::make($r->password);$u=User::create($d);$this->enroll($r,$u);return redirect()->route('admin.users.edit',$u)->with('ok','Пользователь создан');}
 public function edit(User $user){return view('admin.users.form',['item'=>$user,'programs'=>Program::orderBy('title')->get(),'groups'=>Group::orderBy('name')->get(),'enrollments'=>$user->hasMany(Enrollment::class)->with('program','group')->get()]);}
 public function update(Request $r,User $user){$d=$this->data($r,false);if($r->filled('password'))$d['password']=Hash::make($r->password);$user->update($d);$this->enroll($r,$user);return back()->with('ok','Сохранено');}
 public function destroy(User $user){$user->update(['is_active'=>false]);return back()->with('ok','Пользователь заблокирован');}
 private function data(Request $r,bool $new){return $r->validate(['full_name'=>'required|max:255','login'=>'required|max:255|unique:users,login,'.($new?'NULL':$r->route('user')?->id),'email'=>'nullable|email','phone'=>'nullable|string|max:50','role'=>'required|in:student,curator,admin','password'=>$new?'required|min:6':'nullable|min:6'])+['is_active'=>$r->boolean('is_active'),'is_legal_entity'=>$r->boolean('is_legal_entity')];}
 private function enroll(Request $r,User $u){if(!$r->filled('program_id'))return; Enrollment::updateOrCreate(['user_id'=>$u->id,'program_id'=>$r->integer('program_id')],['group_id'=>$r->integer('group_id')?:null,'curator_id'=>$r->integer('curator_id')?:null,'status'=>'active','started_at'=>$r->input('started_at')?:now()->toDateString(),'ends_at'=>$r->input('ends_at')]);}
}
