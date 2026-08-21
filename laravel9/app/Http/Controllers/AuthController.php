<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller {
 public function create(){return view('auth.login');}
 public function store(Request $request){$credentials=$request->validate(['login'=>['required','string'],'password'=>['required','string']]);$credentials['is_active']=true;if(!Auth::attempt($credentials,$request->boolean('remember'))){return back()->withErrors(['login'=>'Неверный логин или пароль.'])->onlyInput('login');}$request->session()->regenerate();DB::table('login_activities')->insert(['user_id'=>$request->user()->id,'ip'=>$request->ip(),'user_agent'=>substr((string)$request->userAgent(),0,500),'logged_in_at'=>now(),'created_at'=>now(),'updated_at'=>now()]);return redirect()->intended(route('dashboard'));}
 public function destroy(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login');}
}
