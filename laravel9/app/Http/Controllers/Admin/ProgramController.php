<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Program; use Illuminate\Http\Request;
class ProgramController extends Controller {
 public function index(){return view('admin.programs.index',['items'=>Program::latest()->paginate(30)]);} public function create(){return view('admin.programs.form',['item'=>new Program]);}
 public function store(Request $r){$d=$this->data($r); Program::create($d); return redirect()->route('admin.programs.index')->with('ok','Программа создана');}
 public function edit(Program $program){return view('admin.programs.form',['item'=>$program]);} public function update(Request $r,Program $program){$program->update($this->data($r));return back()->with('ok','Сохранено');}
 public function destroy(Program $program){$program->update(['is_active'=>false]);return back()->with('ok','Программа деактивирована');}
 private function data(Request $r){return $r->validate(['title'=>'required|max:255','mode'=>'required|in:dpo,nmo','hours'=>'nullable|integer|min:0','starts_at'=>'nullable|date','ends_at'=>'nullable|date|after_or_equal:starts_at','is_active'=>'nullable|boolean'])+['is_active'=>$r->boolean('is_active')];}
}