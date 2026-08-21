<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,FinalWork,FinalWorkDefinition,FinalWorkTheme};
use App\Services\LearningAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinalWorkController extends Controller
{
    public function index(Request $request)
    {
        $enrollments=$request->user()->enrollments()->with('program')->whereIn('status',['active','completed'])->get();
        $programIds=$enrollments->pluck('program_id');
        $definitions=FinalWorkDefinition::with(['programs:id,title','themes'=>fn($q)=>$q->where('is_active',true)])
            ->where('is_active',true)
            ->whereHas('programs',fn($q)=>$q->whereIn('programs.id',$programIds))
            ->get();
        $works=FinalWork::where('user_id',$request->user()->id)->latest()->get()->keyBy(fn($w)=>$w->enrollment_id.':'.($w->final_work_definition_id??0));
        return view('final-works.index',compact('enrollments','definitions','works'));
    }

    public function submit(Request $request, Enrollment $enrollment, LearningAccessService $access)
    {
        abort_unless($enrollment->user_id===$request->user()->id,403);
        $access->enrollment($request->user(),$enrollment->program_id);
        $data=$request->validate([
            'definition_id'=>['required','integer','exists:final_work_definitions,id'],
            'theme_id'=>['nullable','integer','exists:final_work_themes,id'],
            'title'=>['nullable','string','max:500'],
            'comment'=>['nullable','string','max:5000'],
            'file'=>['required','file','max:51200','mimes:pdf,doc,docx,odt,rtf'],
        ]);
        $definition=FinalWorkDefinition::whereKey($data['definition_id'])->where('is_active',true)
            ->whereHas('programs',fn($q)=>$q->where('programs.id',$enrollment->program_id))->firstOrFail();
        $theme=null;
        if(!empty($data['theme_id'])) $theme=FinalWorkTheme::whereKey($data['theme_id'])->where('definition_id',$definition->id)->where('is_active',true)->firstOrFail();

        $work=FinalWork::where('enrollment_id',$enrollment->id)->where('final_work_definition_id',$definition->id)->latest()->first();
        abort_if($work && in_array($work->status,['accepted','passed'],true),422,'Принятая итоговая работа не может быть заменена.');

        $path=$request->file('file')->store('final-works/'.$request->user()->id);
        if($work?->file_path) Storage::delete($work->file_path);
        $payload=[
            'user_id'=>$request->user()->id,'enrollment_id'=>$enrollment->id,
            'final_work_definition_id'=>$definition->id,'final_work_theme_id'=>$theme?->id,
            'title'=>$data['title'] ?: ($theme?->title ?: $definition->title),'file_path'=>$path,
            'comment'=>$data['comment']??null,'status'=>'submitted','submitted_at'=>now(),
            'reviewed_at'=>null,'reviewed_by'=>null,'review_comment'=>null,
        ];
        if($work) $work->update($payload); else FinalWork::create($payload);
        return back()->with('ok','Итоговая работа отправлена на проверку.');
    }

    public function download(Request $request, FinalWork $finalWork)
    {
        $allowed=$finalWork->user_id===$request->user()->id || in_array($request->user()->role,['curator','admin'],true);
        abort_unless($allowed,403);
        abort_unless($finalWork->file_path && Storage::exists($finalWork->file_path),404);
        $ext=pathinfo($finalWork->file_path,PATHINFO_EXTENSION);
        return Storage::download($finalWork->file_path,($finalWork->title?:'final-work').($ext?'.'.$ext:''));
    }
}
