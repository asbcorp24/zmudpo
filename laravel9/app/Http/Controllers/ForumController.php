<?php
namespace App\Http\Controllers;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
class ForumController extends Controller { public function index(Request $request){$programIds=$request->user()->enrollments()->pluck('program_id');$topics=ForumTopic::with('author')->where(function($q)use($programIds){$q->whereNull('program_id')->orWhereIn('program_id',$programIds);})->latest()->paginate(20);return view('forum.index',compact('topics'));} public function store(Request $request){$data=$request->validate(['title'=>['required','string','max:255'],'body'=>['required','string','max:10000'],'program_id'=>['nullable','integer']]);if(!empty($data['program_id'])) abort_unless($request->user()->enrollments()->where('program_id',$data['program_id'])->exists()||$request->user()->isCurator(),403);ForumTopic::create($data+['user_id'=>$request->user()->id]);return back()->with('success','Тема создана.');} }
