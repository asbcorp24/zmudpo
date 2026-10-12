<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ArchiveRecord,Program,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchiveAdminController extends Controller
{
    public function index(Request $r)
    {
        $q=ArchiveRecord::with(['user','program']);
        if($r->filled('type'))$q->where('type',$r->string('type'));
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        if($r->filled('user_id'))$q->where('user_id',$r->integer('user_id'));
        if($r->filled('q')){
            $term=$r->string('q')->toString();
            $q->where(function($x)use($term){
                $x->where('title','like','%'.$term.'%')
                  ->orWhereHas('user',fn($u)=>$u->where('full_name','like','%'.$term.'%'))
                  ->orWhereHas('program',fn($p)=>$p->where('title','like','%'.$term.'%'));
            });
        }
        $items=$q->latest()->paginate(100)->withQueryString();
        return view('admin.legacy.archive',[
            'items'=>$items,
            'types'=>ArchiveRecord::select('type')->distinct()->orderBy('type')->pluck('type'),
            'programs'=>Program::orderBy('title')->get(['id','title']),
        ]);
    }

    public function show(ArchiveRecord $record)
    {
        $record->load(['user','program']);
        return view('admin.legacy.archive-show',compact('record'));
    }

    public function export(Request $r): StreamedResponse
    {
        $q=ArchiveRecord::with(['user','program'])->latest();
        if($r->filled('type'))$q->where('type',$r->string('type'));
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        $filename='archive-'.now()->format('Ymd-His').'.csv';
        return response()->streamDownload(function()use($q){
            $out=fopen('php://output','w');
            fwrite($out,"\xEF\xBB\xBF");
            fputcsv($out,['ID','Тип','Слушатель','Программа','Название','Начало','Окончание','Оценка','Данные'],';');
            $q->chunk(300,function($rows)use($out){
                foreach($rows as $x)fputcsv($out,[
                    $x->id,$x->type,$x->user?->full_name,$x->program?->title,$x->title,
                    optional($x->started_at)->format('d.m.Y'),optional($x->ended_at)->format('d.m.Y'),
                    $x->score,json_encode($x->data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)
                ],';');
            });
            fclose($out);
        },$filename,['Content-Type'=>'text/csv; charset=UTF-8']);
    }

    public function import(Request $r)
    {
        $r->validate(['file'=>'required|file|max:10240']);
        $xml=@simplexml_load_file($r->file('file')->getRealPath());
        if(!$xml||!isset($xml->spec))return back()->withErrors(['file'=>'Не удалось прочитать legacy XML']);
        $spec=$xml->spec;
        DB::transaction(function()use($spec){
            $programLegacy=(int)$spec['num'];
            ArchiveRecord::create([
                'legacy_id'=>$programLegacy,'type'=>'program_snapshot','title'=>(string)$spec['name'],
                'started_at'=>(string)$spec['din']?:null,'ended_at'=>(string)$spec['dout']?:null,
                'data'=>['hours'=>(int)$spec['specchas'],'specialty'=>(string)$spec['specs']]
            ]);
            foreach($spec->student as $s){
                $scores=[];foreach($s->ball as $b)$scores[]=['subject'=>(string)$b['predm'],'score'=>(int)$b['otc'],'hours'=>(int)$b['chas']];
                $extra=[];foreach($s->dopsv as $v)$extra[]=['name'=>(string)$v['nazv'],'type'=>(int)$v['typ'],'string'=>(string)$v['valstr'],'int'=>(string)$v['valint'],'date'=>(string)$v['valdat']];
                ArchiveRecord::create([
                    'legacy_id'=>(int)$s['num'],'type'=>'student_snapshot',
                    'title'=>trim((string)$s['fam'].' '.(string)$s['name'].' '.(string)$s['otch']),
                    'data'=>[
                        'program_legacy_id'=>$programLegacy,'final_work'=>(string)$s['itog_rab'],
                        'red_registry'=>(string)$s['krasn_reg'],'registration_number'=>(string)$s['nreg'],
                        'issue_date'=>(string)$s['datav'],'protocol'=>(string)$s['protocol'],
                        'scores'=>$scores,'extra'=>$extra
                    ]
                ]);
            }
        });
        return back()->with('ok','Архивный XML импортирован');
    }
}
