<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Enrollment,Group,Program,User};
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index(Request $r)
    {
        $q=User::with(['organizationProfile','employees.enrollments.program'])->where('is_legal_entity',true);
        if($r->filled('q')){
            $term=$r->string('q')->toString();
            $q->where(function($x)use($term){
                $x->where('full_name','like','%'.$term.'%')->orWhere('email','like','%'.$term.'%')->orWhere('phone','like','%'.$term.'%')
                  ->orWhereHas('organizationProfile',fn($p)=>$p->where('inn','like','%'.$term.'%')->orWhere('short_name','like','%'.$term.'%'));
            });
        }
        return view('admin.organizations.index',['items'=>$q->latest()->paginate(40)->withQueryString()]);
    }

    public function edit(User $organization)
    {
        abort_unless($organization->is_legal_entity,404);
        $organization->load(['organizationProfile','employees.enrollments.program','employees.enrollments.group']);
        return view('admin.organizations.edit',[
            'organization'=>$organization,
            'programs'=>Program::orderBy('title')->get(),
            'groups'=>Group::orderBy('name')->get(),
        ]);
    }

    public function update(Request $r,User $organization,AuditService $audit)
    {
        abort_unless($organization->is_legal_entity,404);
        $d=$r->validate([
            'full_name'=>'required|string|max:255','email'=>'nullable|email|max:255','phone'=>'nullable|string|max:50',
            'short_name'=>'nullable|string|max:255','inn'=>'nullable|string|max:20','kpp'=>'nullable|string|max:20','ogrn'=>'nullable|string|max:30',
            'legal_address'=>'nullable|string|max:500','actual_address'=>'nullable|string|max:500','contact_person'=>'nullable|string|max:255',
            'contact_position'=>'nullable|string|max:255','contract_number'=>'nullable|string|max:100','contract_date'=>'nullable|date','comment'=>'nullable|string|max:2000',
        ]);
        $before=$organization->toArray();
        DB::transaction(function()use($organization,$d){
            $organization->update(['full_name'=>$d['full_name'],'email'=>$d['email']??null,'phone'=>$d['phone']??null]);
            DB::table('organization_profiles')->updateOrInsert(
                ['user_id'=>$organization->id],
                [
                    'short_name'=>$d['short_name']??null,'inn'=>$d['inn']??null,'kpp'=>$d['kpp']??null,'ogrn'=>$d['ogrn']??null,
                    'legal_address'=>$d['legal_address']??null,'actual_address'=>$d['actual_address']??null,
                    'contact_person'=>$d['contact_person']??null,'contact_position'=>$d['contact_position']??null,
                    'contract_number'=>$d['contract_number']??null,'contract_date'=>$d['contract_date']??null,
                    'comment'=>$d['comment']??null,'updated_at'=>now(),'created_at'=>DB::raw('COALESCE(created_at,NOW())')
                ]
            );
        });
        $audit->write('organization_updated',$organization,$before,$organization->fresh()->toArray());
        return back()->with('ok','Организация сохранена.');
    }

    public function addEmployees(Request $r,User $organization,AuditService $audit)
    {
        abort_unless($organization->is_legal_entity,404);
        $d=$r->validate([
            'program_id'=>'required|exists:programs,id','group_id'=>'nullable|exists:groups,id','names'=>'required|string',
            'started_at'=>'nullable|date','ends_at'=>'nullable|date|after_or_equal:started_at'
        ]);
        $created=0;
        DB::transaction(function()use($d,$organization,&$created){
            foreach(preg_split('/\R/u',trim($d['names'])) as $line){
                $name=trim($line);if($name==='')continue;
                $login='org'.$organization->id.'_'.Str::lower(Str::random(8));
                $user=User::create([
                    'login'=>$login,'full_name'=>$name,'password'=>Hash::make(Str::random(40)),'role'=>'student',
                    'is_active'=>false,'is_legal_entity'=>false,'parent_user_id'=>$organization->id
                ]);
                Enrollment::create([
                    'user_id'=>$user->id,'program_id'=>$d['program_id'],'group_id'=>$d['group_id']??null,'status'=>'active',
                    'started_at'=>$d['started_at']??now()->toDateString(),'ends_at'=>$d['ends_at']??null
                ]);
                $created++;
            }
        });
        $audit->write('organization_employees_created',$organization,[],['count'=>$created,'program_id'=>$d['program_id']]);
        return back()->with('ok','Добавлено сотрудников: '.$created);
    }

    public function employeeUpdate(Request $r,User $organization,User $employee)
    {
        abort_unless($organization->is_legal_entity && $employee->parent_user_id===$organization->id,403);
        $d=$r->validate(['full_name'=>'required|string|max:255','email'=>'nullable|email|max:255','phone'=>'nullable|string|max:50','is_active'=>'nullable|boolean']);
        $employee->update(['full_name'=>$d['full_name'],'email'=>$d['email']??null,'phone'=>$d['phone']??null,'is_active'=>$r->boolean('is_active')]);
        return back()->with('ok','Данные сотрудника сохранены.');
    }

    public function employeeDelete(User $organization,User $employee)
    {
        abort_unless($organization->is_legal_entity && $employee->parent_user_id===$organization->id,403);
        abort_if($employee->enrollments()->whereIn('status',['completed'])->exists(),422,'Завершившего обучение сотрудника нельзя удалить.');
        $employee->delete();
        return back()->with('ok','Сотрудник удалён.');
    }
}
