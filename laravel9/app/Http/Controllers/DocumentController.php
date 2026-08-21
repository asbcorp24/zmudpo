<?php
namespace App\Http\Controllers;
use App\Models\EducationDocument;
use Illuminate\Http\Request;
class DocumentController extends Controller { public function index(Request $request){$documents=EducationDocument::where(function($q)use($request){$q->where('user_id',$request->user()->id)->orWhereNull('user_id');})->latest('issued_at')->get();return view('documents.index',compact('documents'));} }
