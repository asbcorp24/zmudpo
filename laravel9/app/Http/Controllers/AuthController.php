<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,LoginEvent,Program};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
 public function showLogin(Request $request)
 {
  $programs=Program::where('is_active',true)->orderByRaw("mode='nmo'")->orderBy('title')->get();
  $selectedProgram=null;
  if($request->filled('program'))$selectedProgram=Program::where('is_active',true)->find($request->integer('program'));
  elseif($request->filled('spec'))$selectedProgram=Program::where('is_active',true)->where('legacy_id',$request->integer('spec'))->first();
  return view('auth.login',compact('programs','selectedProgram'));
 }
 public function create(Request $request){return $this->showLogin($request);}
 public function login(Request $request){return $this->performLogin($request);}
 public function store(Request $request){return $this->performLogin($request);}
 private function performLogin(Request $request)
 {
  $data=$request->validate(['login'=>['required','string'],'password'=>['required','string'],'program_id'=>['nullable','integer','exists:programs,id']]);
  $programId=$data['program_id']??null;
  $credentials=['login'=>$data['login'],'password'=>$data['password'],'is_active'=>true];
  if(!Auth::attempt($credentials,$request->boolean('remember')))return back()->withErrors(['login'=>'Неверный логин или пароль.'])->onlyInput('login','program_id');
  $request->session()->regenerate();$u=$request->user();
  if($programId && !$u->isAdmin() && !$u->isCurator()){
   $enrollment=Enrollment::where('user_id',$u->id)->where('program_id',$programId)->whereIn('status',['active','completed'])->first();
   if(!$enrollment){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login',['program'=>$programId])->withErrors(['login'=>'Этот пользователь не прикреплён к выбранной программе.']);}
  }
  DB::table('login_activities')->insert(['user_id'=>$u->id,'ip'=>$request->ip(),'user_agent'=>substr((string)$request->userAgent(),0,500),'logged_in_at'=>now(),'created_at'=>now(),'updated_at'=>now()]);
  if(DB::getSchemaBuilder()->hasTable('login_events'))LoginEvent::create(['user_id'=>$u->id,'logged_in_at'=>now(),'ip'=>$request->ip(),'user_agent'=>substr((string)$request->userAgent(),0,500)]);
  if($programId && isset($enrollment))return redirect()->route('programs.show',$enrollment);
  return redirect()->intended(route('dashboard'));
 }
 public function logout(Request $request){return $this->performLogout($request);}
 public function destroy(Request $request){return $this->performLogout($request);}
 private function performLogout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login');}
}
