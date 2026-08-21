<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\{Program,ProgramType,CertificateTemplate};use Illuminate\Http\Request;
class ProgramController extends Controller {
 private function form(Program $item){return view('admin.programs.form',['item'=>$item,'types'=>ProgramType::orderBy('name')->get(),'certificates'=>CertificateTemplate::where('is_active',1)->orderBy('name')->get()]);}
 public function index(){return view('admin.programs.index',['items'=>Program::latest()->paginate(30)]);} public function create(){return $this->form(new Program);}
 public function store(Request $r){$p=Program::create($this->data($r));return redirect()->route('admin.programs.edit',$p)->with('ok','Программа создана');}
 public function edit(Program $program){return $this->form($program);} public function update(Request $r,Program $program){$program->update($this->data($r));return back()->with('ok','Сохранено');}
 public function destroy(Program $program){$program->update(['is_active'=>false]);return back()->with('ok','Программа деактивирована');}
 private function data(Request $r){$d=$r->validate(['title'=>'required|max:255','mode'=>'required|in:dpo,nmo','program_type_id'=>'nullable|exists:program_types,id','certificate_template_id'=>'nullable|exists:certificate_templates,id','hours'=>'nullable|integer|min:0','starts_at'=>'nullable|date','ends_at'=>'nullable|date|after_or_equal:starts_at','about'=>'nullable|string']);return $d+['is_active'=>$r->boolean('is_active'),'registration_enabled'=>$r->boolean('registration_enabled'),'featured'=>$r->boolean('featured')];}
}
