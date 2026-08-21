<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\{LearningSection,Program}; use Illuminate\Http\Request;
class SectionController extends Controller {
 public function index(Request $r){return view('admin.sections.index',['items'=>LearningSection::with('program')->when($r->program_id,fn($q,$v)=>$q->where('program_id',$v))->orderBy('program_id')->orderBy('position')->paginate(50),'programs'=>Program::orderBy('title')->get()]);}
 public function store(Request $r){LearningSection::create($this->data($r));return back()->with('ok','Раздел добавлен');} public function update(Request $r,LearningSection $section){$section->update($this->data($r));return back()->with('ok','Раздел сохранён');} public function destroy(LearningSection $section){$section->update(['is_active'=>false]);return back()->with('ok','Раздел отключён');}
 private function data(Request $r){return $r->validate(['program_id'=>'required|exists:programs,id','title'=>'required|max:255','description'=>'nullable','type'=>'required|max:30','position'=>'nullable|integer|min:0','available_from'=>'nullable|date','available_until'=>'nullable|date','prerequisite_section_id'=>'nullable|exists:learning_sections,id'])+['is_active'=>$r->boolean('is_active'),'is_required'=>$r->boolean('is_required')];}
}