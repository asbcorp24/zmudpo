<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{CertificateTemplate,EducationDocument,Enrollment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentAdminController extends Controller
{
    public function index(Request $r)
    {
        $q=EducationDocument::with(['user','program']);
        if($r->filled('user_id'))$q->where('user_id',$r->integer('user_id'));
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        if($r->filled('type'))$q->where('type',$r->string('type'));
        return view('admin.documents.index',[
            'items'=>$q->latest('issued_at')->paginate(40)->withQueryString(),
            'completed'=>Enrollment::with('user','program')->where('status','completed')->latest('completed_at')->limit(300)->get(),
            'templates'=>CertificateTemplate::where('is_active',1)->orderBy('document_type')->orderBy('name')->get()
        ]);
    }

    public function store(Request $r)
    {
        $d=$r->validate([
            'user_id'=>'required|exists:users,id',
            'program_id'=>'required|exists:programs,id',
            'type'=>'required|in:certificate,diploma,certificate_nmo,statement,other',
            'title'=>'required|string|max:255',
            'number'=>'nullable|string|max:100',
            'issued_at'=>'nullable|date',
            'file'=>'nullable|file|max:20480',
            'protocol'=>'nullable|string|max:255',
            'hours'=>'nullable|integer|min:0',
            'qualification'=>'nullable|string|max:500',
            'registration_number'=>'nullable|string|max:100',
            'certificate_template_id'=>'nullable|exists:certificate_templates,id',
        ]);
        $enrollment=Enrollment::with(['user','program'])->where('user_id',$d['user_id'])->where('program_id',$d['program_id'])->where('status','completed')->first();
        if(!$enrollment)return back()->withErrors(['document'=>'Документ можно выдать только после завершения программы.']);

        $filePath=$r->hasFile('file')?$r->file('file')->store('education-documents/'.$d['user_id']):null;
        $meta=[
            'enrollment_id'=>$enrollment->id,
            'protocol'=>$d['protocol']??null,
            'hours'=>$d['hours']??$enrollment->program?->hours,
            'qualification'=>$d['qualification']??null,
            'registration_number'=>$d['registration_number']??null,
            'issued_by'=>$r->user()->id,
        ];
        unset($d['file'],$d['protocol'],$d['hours'],$d['qualification'],$d['registration_number']);
        if(empty($d['certificate_template_id']))$d['certificate_template_id']=CertificateTemplate::where('document_type',$d['type'])->where('is_active',1)->orderByDesc('is_default')->value('id');
        EducationDocument::create($d+['file_path'=>$filePath,'issued_at'=>$d['issued_at']??now()->toDateString(),'meta'=>$meta]);
        return back()->with('ok','Документ выдан.');
    }

    public function print(EducationDocument $document)
    {
        $document->load(['user','program','template']);$template=$document->template;
        return view('admin.documents.print',compact('document','template'));
    }

    public function destroy(EducationDocument $document)
    {
        if($document->file_path)Storage::delete($document->file_path);
        $document->delete();
        return back()->with('ok','Документ отозван.');
    }
}
