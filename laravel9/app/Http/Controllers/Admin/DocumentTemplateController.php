<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        return view('admin.document-templates.index',[
            'items'=>CertificateTemplate::orderByDesc('is_default')->orderBy('document_type')->orderBy('name')->get()
        ]);
    }

    public function store(Request $r)
    {
        $data=$this->data($r);
        if($r->boolean('is_default'))CertificateTemplate::where('document_type',$data['document_type'])->update(['is_default'=>false]);
        CertificateTemplate::create($data+['is_default'=>$r->boolean('is_default'),'is_active'=>$r->boolean('is_active')]);
        return back()->with('ok','Шаблон создан.');
    }

    public function update(Request $r,CertificateTemplate $template)
    {
        $data=$this->data($r);
        if($r->boolean('is_default'))CertificateTemplate::where('document_type',$data['document_type'])->whereKeyNot($template->id)->update(['is_default'=>false]);
        $template->update($data+['is_default'=>$r->boolean('is_default'),'is_active'=>$r->boolean('is_active')]);
        return back()->with('ok','Шаблон сохранён.');
    }

    public function destroy(CertificateTemplate $template)
    {
        if($template->documents()->exists())return back()->withErrors(['template'=>'Шаблон уже используется в выданных документах.']);
        $template->delete();
        return back()->with('ok','Шаблон удалён.');
    }

    public function preview(CertificateTemplate $template)
    {
        $document=(object)[
            'title'=>$template->heading?:$template->name,
            'type'=>$template->document_type,
            'number'=>'000001',
            'issued_at'=>now(),
            'meta'=>['hours'=>144,'qualification'=>'Повышение квалификации','registration_number'=>'Р-0001','protocol'=>'№ 1 от '.now()->format('d.m.Y')],
            'user'=>(object)['full_name'=>'Иванов Иван Иванович'],
            'program'=>(object)['title'=>'Пример образовательной программы']
        ];
        return view('admin.documents.print',compact('document','template'));
    }

    private function data(Request $r): array
    {
        return $r->validate([
            'name'=>'required|string|max:255',
            'document_type'=>'required|in:certificate,diploma,certificate_nmo,statement,other',
            'orientation'=>'required|in:portrait,landscape',
            'organization_name'=>'nullable|string|max:500',
            'heading'=>'nullable|string|max:255',
            'subheading'=>'nullable|string|max:500',
            'body_template'=>'nullable|string|max:5000',
            'footer_left'=>'nullable|string|max:500',
            'footer_right'=>'nullable|string|max:500',
        ]);
    }
}
