<?php
namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\{Announcement,ContentItem,Enrollment,Group,LearningSection,LearningSectionProgress,QuizAttempt,SectionProgress,Submission,FinalWork,Survey,SurveyResponse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LegacyCabinetController extends Controller
{
    private function enrollments(Request $r)
    {
        $q=Enrollment::with(['user','program','group'])->whereIn('status',['active','completed']);
        if(!$r->user()->isAdmin())$q->where('curator_id',$r->user()->id);
        return $q;
    }
    private function allowedEnrollment(Request $r,Enrollment $e): void
    { abort_unless($r->user()->isAdmin() || $e->curator_id===$r->user()->id,403); }
    private function programIds(Request $r){return $this->enrollments($r)->pluck('program_id')->unique();}
    private function studentIds(Request $r){return $this->enrollments($r)->pluck('user_id')->unique();}

    public function students(Request $r)
    {
        $q=$this->enrollments($r);
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        if($r->filled('group_id'))$q->where('group_id',$r->integer('group_id'));
        if($r->filled('q'))$q->whereHas('user',fn($x)=>$x->where('full_name','like','%'.$r->q.'%')->orWhere('login','like','%'.$r->q.'%'));
        $items=$q->orderByDesc('status')->latest('id')->paginate(60)->withQueryString();
        $ids=$items->pluck('user_id');
        $lastLogins=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->selectRaw('user_id,max(logged_in_at) last_login')->groupBy('user_id')->pluck('last_login','user_id'):collect();
        return view('curator.students',compact('items','lastLogins')+['programs'=>$this->enrollments($r)->get()->pluck('program','program_id')->filter(),'groups'=>Group::orderBy('name')->get()]);
    }

    public function student(Request $r,Enrollment $enrollment)
    {
        $this->allowedEnrollment($r,$enrollment);$enrollment->load(['user','program','group','finalWorks']);
        $sections=LearningSection::where('program_id',$enrollment->program_id)->orderBy('position')->get();
        $progress=SectionProgress::where('user_id',$enrollment->user_id)->whereIn('learning_section_id',$sections->pluck('id'))->get()->keyBy('learning_section_id');
        $nmo=LearningSectionProgress::where('user_id',$enrollment->user_id)->where('program_id',$enrollment->program_id)->latest('legacy_date')->get();
        $attempts=QuizAttempt::with('assignment')->where('user_id',$enrollment->user_id)->whereHas('assignment',fn($q)=>$q->where('program_id',$enrollment->program_id))->latest('finished_at')->get();
        $submissions=Submission::with('practiceAssignment')->where('user_id',$enrollment->user_id)->whereHas('practiceAssignment',fn($q)=>$q->where('program_id',$enrollment->program_id))->latest('submitted_at')->get();
        $logins=Schema::hasTable('login_activities')?DB::table('login_activities')->where('user_id',$enrollment->user_id)->latest('logged_in_at')->limit(100)->get():collect();
        return view('curator.student',compact('enrollment','sections','progress','nmo','attempts','submissions','logins'));
    }

    public function attendance(Request $r)
    {
        $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');
        $logins=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->latest('logged_in_at')->limit(1000)->get()->groupBy('user_id'):collect();
        return view('curator.attendance',compact('enrollments','logins'));
    }

    public function practice(Request $r)
    {
        $ids=$this->studentIds($r);$pids=$this->programIds($r);
        $items=Submission::with(['user','practiceAssignment'])->whereIn('user_id',$ids)->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$pids))->latest('submitted_at')->paginate(80);
        return view('curator.practice',compact('items'));
    }

    public function files(Request $r)
    {
        $ids=$this->studentIds($r);$pids=$this->programIds($r);
        $submissions=Submission::with(['user','practiceAssignment'])->whereIn('user_id',$ids)->whereNotNull('file_path')->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$pids))->latest('submitted_at')->get();
        $finals=FinalWork::with(['user','program'])->whereIn('user_id',$ids)->whereIn('program_id',$pids)->whereNotNull('file_path')->latest('submitted_at')->get();
        return view('curator.files',compact('submissions','finals'));
    }

    public function content(Request $r)
    {
        $pids=$this->programIds($r);$sections=LearningSection::with('contentItems')->whereIn('program_id',$pids)->orderBy('program_id')->orderBy('position')->get();
        return view('curator.content',compact('sections'));
    }
    public function updateContent(Request $r,ContentItem $item)
    {
        $item->load('section');abort_unless($this->programIds($r)->contains($item->section?->program_id) || $r->user()->isAdmin(),403);
        $d=$r->validate(['title'=>'required|string|max:255','body'=>'nullable|string','external_url'=>'nullable|url|max:2000','available_from'=>'nullable|date','available_until'=>'nullable|date|after_or_equal:available_from']);
        $item->update($d+['is_required'=>$r->boolean('is_required')]);return back()->with('ok','Материал сохранён.');
    }

    public function announcements(Request $r)
    {
        $pids=$this->programIds($r);$items=Announcement::with('program')->where(function($q)use($pids){$q->whereNull('program_id')->orWhereIn('program_id',$pids);})->latest('published_at')->paginate(50);
        return view('curator.announcements',compact('items')+['programs'=>$this->enrollments($r)->get()->pluck('program','program_id')->filter()]);
    }
    public function storeAnnouncement(Request $r)
    {
        $d=$r->validate(['title'=>'required|string|max:255','body'=>'required|string','program_id'=>'required|exists:programs,id','published_at'=>'nullable|date']);
        abort_unless($this->programIds($r)->contains((int)$d['program_id']) || $r->user()->isAdmin(),403);
        Announcement::create($d+['author_id'=>$r->user()->id,'is_active'=>true,'published_at'=>$d['published_at']??now()]);return back()->with('ok','Объявление опубликовано.');
    }

    public function logins(Request $r)
    {
        $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');
        $items=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->latest('logged_in_at')->paginate(100):collect();
        return view('curator.logins',compact('enrollments','items'));
    }

    public function surveys(Request $r)
    {
        $ids=$this->studentIds($r);$surveys=Survey::with('fields')->get();
        $responses=SurveyResponse::whereIn('user_id',$ids)->get()->groupBy('user_id');
        $students=$this->enrollments($r)->get()->pluck('user','user_id')->filter();
        return view('curator.surveys',compact('surveys','responses','students'));
    }

    public function statistics(Request $r)
    {
        $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');$pids=$enrollments->pluck('program_id');
        $quiz=QuizAttempt::whereIn('user_id',$ids)->whereHas('assignment',fn($q)=>$q->whereIn('program_id',$pids))->selectRaw('user_id,count(*) attempts,avg(percent) avg_percent,sum(case when passed=1 then 1 else 0 end) passed')->groupBy('user_id')->get()->keyBy('user_id');
        $practice=Submission::whereIn('user_id',$ids)->selectRaw('user_id,count(*) total,sum(case when status in (\'accepted\',\'passed\') then 1 else 0 end) accepted')->groupBy('user_id')->get()->keyBy('user_id');
        return view('curator.statistics',compact('enrollments','quiz','practice'));
    }

    public function tables(Request $r){return $this->statistics($r);}

    public function groups(Request $r){return view('curator.groups',['items'=>$this->enrollments($r)->orderBy('group_id')->get(),'groups'=>Group::orderBy('name')->get()]);}
    public function updateGroup(Request $r,Enrollment $enrollment)
    {
        $this->allowedEnrollment($r,$enrollment);$d=$r->validate(['group_id'=>'nullable|exists:groups,id']);$enrollment->update($d);return back()->with('ok','Группа слушателя изменена.');
    }
}
