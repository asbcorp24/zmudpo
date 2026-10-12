<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Hash,Password};
use Illuminate\Support\Str;

class OrganizationPortalController extends Controller
{
    private function org(Request $r): User
    {
        $user=$r->user();
        abort_unless($user->is_legal_entity,403);
        return $user;
    }

    public function index(Request $r)
    {
        $org=$this->org($r)->load(['organizationProfile','employees.enrollments.program','employees.enrollments.group','enrollments.program']);
        $programs=$org->enrollments()->with('program')->where('status','active')->get();
        return view('organization.index',compact('org','programs'));
    }

    public function store(Request $r)
    {
        $org=$this->org($r);
        $d=$r->validate(['program_id'=>'required|exists:programs,id','full_name'=>'required|string|max:255','email'=>'nullable|email|max:255|unique:users,email']);
        $orgEnrollment=$org->enrollments()->where('program_id',$d['program_id'])->where('status','active')->firstOrFail();
        $employee=DB::transaction(function()use($d,$org,$orgEnrollment){
            $u=User::create([
                'login'=>'org'.$org->id.'_'.Str::lower(Str::random(8)),
                'full_name'=>$d['full_name'],'email'=>$d['email']??null,
                'password'=>Hash::make(Str::random(40)),'role'=>'student','is_active'=>false,
                'is_legal_entity'=>false,'parent_user_id'=>$org->id
            ]);
            Enrollment::create([
                'user_id'=>$u->id,'program_id'=>$d['program_id'],'group_id'=>$orgEnrollment->group_id,
                'curator_id'=>$orgEnrollment->curator_id,'status'=>'active',
                'started_at'=>$orgEnrollment->started_at??now()->toDateString(),'ends_at'=>$orgEnrollment->ends_at
            ]);
            return $u;
        });
        return back()->with('ok','Сотрудник добавлен: '.$employee->full_name);
    }

    public function update(Request $r,User $employee)
    {
        $org=$this->org($r);abort_unless($employee->parent_user_id===$org->id,403);
        $d=$r->validate(['full_name'=>'required|string|max:255','email'=>'nullable|email|max:255|unique:users,email,'.$employee->id]);
        $employee->update($d);
        return back()->with('ok','Данные сотрудника сохранены.');
    }

    public function invite(Request $r,User $employee)
    {
        $org=$this->org($r);abort_unless($employee->parent_user_id===$org->id,403);
        abort_unless($employee->email,422,'Сначала укажите e-mail сотрудника.');
        if(!$employee->is_active)$employee->update(['is_active'=>true]);
        Password::sendResetLink(['email'=>$employee->email]);
        return back()->with('ok','Приглашение и ссылка установки пароля отправлены.');
    }

    public function destroy(Request $r,User $employee)
    {
        $org=$this->org($r);abort_unless($employee->parent_user_id===$org->id,403);
        abort_if($employee->is_active,422,'Активного сотрудника нельзя удалить.');
        abort_if($employee->enrollments()->where('status','completed')->exists(),422,'Сотрудник уже завершил обучение.');
        $employee->delete();
        return back()->with('ok','Сотрудник удалён.');
    }
}
