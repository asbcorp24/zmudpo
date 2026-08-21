<?php
namespace App\Http\Controllers;
use App\Models\EducationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller {
 public function index(Request $request){
  $documents=EducationDocument::with('program')->where(function($q)use($request){$q->where('user_id',$request->user()->id)->orWhereNull('user_id');})->latest('issued_at')->get();
  return view('documents.index',compact('documents'));
 }
 public function download(Request $request, EducationDocument $document){
  abort_unless($document->user_id===null || $document->user_id===$request->user()->id,403);
  abort_unless($document->file_path && Storage::exists($document->file_path),404);
  $name=trim(($document->title?:'document').'.'.pathinfo($document->file_path,PATHINFO_EXTENSION),'.');
  return Storage::download($document->file_path,$name);
 }
}
