<?php
namespace App\Http\Controllers;
use App\Models\PracticeAssignment;
use App\Models\Submission;
use Illuminate\Http\Request;
class PracticeController extends Controller { public function index(Request $request){$programIds=$request->user()->enrollments()->pluck('program_id');$assignments=PracticeAssignment::whereIn('program_id',$programIds)->where('is_active',true)->orderBy('ends_at')->get();$submissions=Submission::where('user_id',$request->user()->id)->get()->keyBy('practice_assignment_id');return view('practice.index',compact('assignments','submissions'));} public function submit(Request $request,PracticeAssignment $assignment){abort_unless($request->user()->enrollments()->where('program_id',$assignment->program_id)->exists(),403);$data=$request->validate(['file'=>['nullable','file','max:20480'],'comment'=>['nullable','string','max:5000']]);$path=$request->file('file')?->store('submissions');Submission::updateOrCreate(['practice_assignment_id'=>$assignment->id,'user_id'=>$request->user()->id],['file_path'=>$path,'comment'=>$data['comment']??null,'status'=>'submitted','submitted_at'=>now()]);return back()->with('success','Работа отправлена куратору.');} }
