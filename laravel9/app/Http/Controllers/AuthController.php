<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,LoginEvent,Program,ProgramType,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
 public function showLogin(Request $request)
 {
  $programs=Program::with('type')->where('is_active',true)->orderBy('title')->get();
  $typeIds=$programs->pluck('program_type_id')->filter()->unique()->values();
  $programTypes=ProgramType::where('is_active',true)->whereIn('id',$typeIds)->orderBy('legacy_id')->get();
  $selectedProgram=null;
  if($request->filled('program'))$selectedProgram=Program::with('type')->where('is_active',true)->find($request->integer('program'));
  elseif($request->filled('spec'))$selectedProgram=Program::with('type')->where('is_active',true)->where('legacy_id',$request->integer('spec'))->first();

  $individuals=collect();
  $legalEntities=collect();
  if($selectedProgram){
   $users=User::query()
    ->where('is_active',true)
    ->where('role','student')
    ->whereHas('enrollments',fn($q)=>$q->where('program_id',$selectedProgram->id)->where('status','active'))
    ->orderBy('full_name')
    ->get(['id','login','full_name','is_legal_entity']);
   $individuals=$users->where('is_legal_entity',false)->values();
   $legalEntities=$users->where('is_legal_entity',true)->values();
  }

  return view('auth.login',compact('programs','programTypes','selectedProgram','individuals','legalEntities'));
 }
 public function create(Request $request){return $this->showLogin($request);}
 public function login(Request $request){return $this->performLogin($request);}
 public function store(Request $request){return $this->performLogin($request);}
 private function performLogin(Request $request)
 {
  $data=$request->validate(['login'=>['required','string'],'password'=>['required','string'],'program_id'=>['nullable','integer','exists:programs,id']]);
  $programId=$data['program_id']??null;
  $credentials=['login'=>$data['login'],'password'=>$data['password'],'is_active'=>true];
  if(!Auth::attempt($credentials,$request->boolean('remember')))return back()->withErrors(['login'=>'Неверный пользователь или пароль.'])->onlyInput('login','program_id');
  $request->session()->regenerate();$u=$request->user();
  if($programId && !$u->isAdmin() && !$u->isCurator()){
   $enrollment=Enrollment::where('user_id',$u->id)->where('program_id',$programId)->where('status','active')->first();
   if(!$enrollment){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login',['program'=>$programId])->withErrors(['login'=>'Этот пользователь не прикреплён к выбранной активной группе.']);}
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
