<?php
namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\{Announcement,AnnouncementUserAcknowledgement,AttendanceRecord,ContentItem,ContentItemResponse,ContentItemTicketAssignment,Enrollment,Group,LearningSection,LearningSectionProgress,LearningSectionVisit,LegacyCuratorRecord,NmoPracticeDiaryEntry,QuizAttempt,SectionProgress,Submission,FinalWork,Survey,SurveyResponse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LegacyCabinetController extends Controller
{
    private function enrollments(Request $r){$q=Enrollment::with(['user','program','group'])->whereIn('status',['active','completed']);if(!$r->user()->isAdmin())$q->where('curator_id',$r->user()->id);return $q;}
    private function allowedEnrollment(Request $r,Enrollment $e): void {abort_unless($r->user()->isAdmin()||$e->curator_id===$r->user()->id,403);}
    private function programIds(Request $r){return $this->enrollments($r)->pluck('program_id')->unique();}
    private function studentIds(Request $r){return $this->enrollments($r)->pluck('user_id')->unique();}
    private function allowedUser(Request $r,?int $userId): bool{return $userId&&($r->user()->isAdmin()||$this->studentIds($r)->contains($userId));}

    public function students(Request $r){$q=$this->enrollments($r);if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));if($r->filled('group_id'))$q->where('group_id',$r->integer('group_id'));if($r->filled('q')){$term=$r->string('q')->toString();$q->whereHas('user',fn($x)=>$x->where(fn($u)=>$u->where('full_name','like','%'.$term.'%')->orWhere('login','like','%'.$term.'%')));}$items=$q->orderByDesc('status')->latest('id')->paginate(60)->withQueryString();$ids=$items->pluck('user_id');$lastLogins=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->selectRaw('user_id,max(logged_in_at) last_login')->groupBy('user_id')->pluck('last_login','user_id'):collect();return view('curator.students',compact('items','lastLogins')+['programs'=>$this->enrollments($r)->get()->pluck('program','program_id')->filter(),'groups'=>Group::orderBy('name')->get()]);}

    public function student(Request $r,Enrollment $enrollment){
        $this->allowedEnrollment($r,$enrollment);
        $enrollment->load(['user','program','group','finalWorks']);
        $sections=LearningSection::with('contentItems')->where('program_id',$enrollment->program_id)->orderBy('position')->get();
        $itemIds=$sections->flatMap->contentItems->pluck('id');
        $progress=SectionProgress::where('user_id',$enrollment->user_id)->whereIn('learning_section_id',$sections->pluck('id'))->get()->keyBy('learning_section_id');
        $nmo=LearningSectionProgress::where('user_id',$enrollment->user_id)->where('program_id',$enrollment->program_id)->latest('legacy_date')->get();
        $attempts=QuizAttempt::with('assignment')->where('user_id',$enrollment->user_id)->whereHas('assignment',fn($q)=>$q->where('program_id',$enrollment->program_id))->latest('finished_at')->get();
        $submissions=Submission::with('practiceAssignment')->where('user_id',$enrollment->user_id)->whereHas('practiceAssignment',fn($q)=>$q->where('program_id',$enrollment->program_id))->latest('submitted_at')->get();
        $responses=ContentItemResponse::with('item.section')->where('user_id',$enrollment->user_id)->whereIn('content_item_id',$itemIds)->latest('submitted_at')->get();
        $tickets=ContentItemTicketAssignment::with('item.section')->where('user_id',$enrollment->user_id)->whereIn('content_item_id',$itemIds)->latest()->get();
        $diary=NmoPracticeDiaryEntry::with('item.section')->where('user_id',$enrollment->user_id)->whereIn('content_item_id',$itemIds)->latest('work_date')->get();
        $visits=LearningSectionVisit::with('section')->where('user_id',$enrollment->user_id)->whereIn('learning_section_id',$sections->pluck('id'))->latest('visited_at')->get();
        $acks=AnnouncementUserAcknowledgement::with('announcement')->where('user_id',$enrollment->user_id)->latest('acknowledged_at')->get();
        $logins=Schema::hasTable('login_activities')?DB::table('login_activities')->where('user_id',$enrollment->user_id)->latest('logged_in_at')->limit(100)->get():collect();
        $legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::where('user_id',$enrollment->user_id)->latest('occurred_at')->get():collect();
        return view('curator.student',compact('enrollment','sections','progress','nmo','attempts','submissions','responses','tickets','diary','visits','acks','logins','legacy'));
    }

    public function attendance(Request $r){
        $q=$this->enrollments($r);
        if($r->filled('program_id'))$q->where('program_id',$r->integer('program_id'));
        if($r->filled('group_id'))$q->where('group_id',$r->integer('group_id'));
        $enrollments=$q->get();$ids=$enrollments->pluck('user_id');
        $date=$r->input('date',now()->toDateString());
        $records=AttendanceRecord::whereIn('enrollment_id',$enrollments->pluck('id'))->whereDate('attendance_date',$date)->get()->keyBy('enrollment_id');
        $logins=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->whereDate('logged_in_at',$date)->latest('logged_in_at')->get()->groupBy('user_id'):collect();
        $legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::where('type','attendance')->whereIn('user_id',$ids)->latest('occurred_at')->get()->groupBy('user_id'):collect();
        return view('curator.attendance',compact('enrollments','records','logins','legacy','date')+['programs'=>$this->enrollments($r)->get()->pluck('program','program_id')->filter(),'groups'=>Group::orderBy('name')->get()]);
    }

    public function markAttendance(Request $r,Enrollment $enrollment){
        $this->allowedEnrollment($r,$enrollment);
        $d=$r->validate(['attendance_date'=>'required|date','status'=>'required|in:present,absent,excused,late','minutes'=>'nullable|integer|min:0|max:1440','comment'=>'nullable|string|max:1000']);
        AttendanceRecord::updateOrCreate(['enrollment_id'=>$enrollment->id,'attendance_date'=>$d['attendance_date']],$d+['marked_by'=>$r->user()->id]);
        return back()->with('ok','Посещаемость сохранена.');
    }

    public function practice(Request $r){$ids=$this->studentIds($r);$pids=$this->programIds($r);$items=Submission::with(['user','practiceAssignment'])->whereIn('user_id',$ids)->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$pids))->latest('submitted_at')->paginate(80);$legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::with('user')->where('type','practice')->whereIn('user_id',$ids)->latest('occurred_at')->get():collect();return view('curator.practice',compact('items','legacy'));}

    public function files(Request $r){$ids=$this->studentIds($r);$pids=$this->programIds($r);$submissions=Submission::with(['user','practiceAssignment'])->whereIn('user_id',$ids)->whereNotNull('file_path')->whereHas('practiceAssignment',fn($q)=>$q->whereIn('program_id',$pids))->latest('submitted_at')->get();$finals=FinalWork::with(['user','enrollment.program'])->whereIn('user_id',$ids)->whereNotNull('file_path')->whereHas('enrollment',fn($q)=>$q->whereIn('program_id',$pids))->latest('submitted_at')->get();$legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::with('user')->where('type','user_file')->whereIn('user_id',$ids)->latest('occurred_at')->get():collect();return view('curator.files',compact('submissions','finals','legacy'));}

    public function content(Request $r){
        $enrollments=$this->enrollments($r)->get();
        $ids=$enrollments->pluck('user_id');$pids=$enrollments->pluck('program_id')->unique();
        $sections=LearningSection::with(['program','contentItems'])->whereIn('program_id',$pids)->orderBy('program_id')->orderBy('position')->get();
        $items=$sections->flatMap->contentItems;
        $responses=ContentItemResponse::with(['user','item.section'])->whereIn('user_id',$ids)->whereIn('content_item_id',$items->pluck('id'))->latest('submitted_at')->get();
        $tickets=ContentItemTicketAssignment::with(['user','item.section'])->whereIn('user_id',$ids)->whereIn('content_item_id',$items->pluck('id'))->latest()->get();
        $diary=NmoPracticeDiaryEntry::with(['user','item.section'])->whereIn('user_id',$ids)->whereIn('content_item_id',$items->pluck('id'))->latest('work_date')->get();
        $visits=LearningSectionVisit::with(['user','section.program'])->whereIn('user_id',$ids)->whereIn('learning_section_id',$sections->pluck('id'))->latest('visited_at')->limit(1000)->get();
        $attempts=QuizAttempt::with(['user','assignment'])->whereIn('user_id',$ids)->whereHas('assignment',fn($q)=>$q->whereIn('program_id',$pids))->latest('finished_at')->limit(1000)->get();
        $acks=AnnouncementUserAcknowledgement::with(['user','announcement'])->whereIn('user_id',$ids)->latest('acknowledged_at')->limit(1000)->get();
        return view('curator.content',compact('sections','enrollments','responses','tickets','diary','visits','attempts','acks'));
    }

    public function documents(Request $r){$ids=$this->studentIds($r);$items=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::with('user')->where('type','workbook')->whereIn('user_id',$ids)->latest()->paginate(80):null;return view('curator.documents',compact('items'));}
    public function downloadLegacy(Request $r,LegacyCuratorRecord $record){abort_unless($this->allowedUser($r,$record->user_id),403);abort_unless($record->archive_path&&Storage::disk('local')->exists($record->archive_path),404);return Storage::disk('local')->download($record->archive_path,basename($record->path?:$record->archive_path));}

    public function announcements(Request $r){$pids=$this->programIds($r);$items=Announcement::with('program')->where(fn($q)=>$q->whereNull('program_id')->orWhereIn('program_id',$pids))->latest('published_at')->paginate(50);return view('curator.announcements',compact('items')+['programs'=>$this->enrollments($r)->get()->pluck('program','program_id')->filter()]);}
    public function storeAnnouncement(Request $r){$d=$r->validate(['title'=>'required|string|max:255','body'=>'required|string','program_id'=>'required|exists:programs,id','published_at'=>'nullable|date']);abort_unless($r->user()->isAdmin()||$this->programIds($r)->contains((int)$d['program_id']),403);Announcement::create($d+['author_id'=>$r->user()->id,'is_active'=>true,'published_at'=>$d['published_at']??now()]);return back()->with('ok','Объявление опубликовано.');}

    public function logins(Request $r){
        $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');
        $q=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids):null;
        if($q&&$r->filled('ip'))$q->where('ip','like','%'.$r->string('ip')->toString().'%');
        if($q&&$r->filled('date_from'))$q->whereDate('logged_in_at','>=',$r->date('date_from'));
        if($q&&$r->filled('date_to'))$q->whereDate('logged_in_at','<=',$r->date('date_to'));
        $items=$q?$q->latest('logged_in_at')->paginate(100)->withQueryString():null;
        $legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::with('user')->where('type','login')->whereIn('user_id',$ids)->latest('occurred_at')->paginate(100,'*','legacy_page'):null;
        $duplicates=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->whereNotNull('ip')->selectRaw('ip,count(distinct user_id) users_count,count(*) logins_count')->groupBy('ip')->havingRaw('count(distinct user_id)>1')->orderByDesc('users_count')->limit(100)->get():collect();
        return view('curator.logins',compact('enrollments','items','legacy','duplicates'));
    }
    public function surveys(Request $r){$ids=$this->studentIds($r);$surveys=Survey::with('fields')->get();$responses=SurveyResponse::whereIn('user_id',$ids)->get()->groupBy('user_id');$students=$this->enrollments($r)->get()->pluck('user','user_id')->filter();return view('curator.surveys',compact('surveys','responses','students'));}
    public function statistics(Request $r){
        $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');$pids=$enrollments->pluck('program_id');
        $quiz=QuizAttempt::whereIn('user_id',$ids)->whereHas('assignment',fn($q)=>$q->whereIn('program_id',$pids))->selectRaw('user_id,count(*) attempts,avg(percent) avg_percent,sum(case when passed=1 then 1 else 0 end) passed')->groupBy('user_id')->get()->keyBy('user_id');
        $practice=Submission::whereIn('user_id',$ids)->selectRaw("user_id,count(*) total,sum(case when status in ('accepted','passed') then 1 else 0 end) accepted")->groupBy('user_id')->get()->keyBy('user_id');
        $content=ContentItemResponse::whereIn('user_id',$ids)->whereHas('item.section',fn($q)=>$q->whereIn('program_id',$pids))->selectRaw("user_id,count(*) total,sum(case when status='accepted' then 1 else 0 end) accepted,sum(case when status='submitted' then 1 else 0 end) pending")->groupBy('user_id')->get()->keyBy('user_id');
        return view('curator.statistics',compact('enrollments','quiz','practice','content'));
    }
    public function tables(Request $r){return $this->statistics($r);}
    public function groups(Request $r){return view('curator.groups',['items'=>$this->enrollments($r)->orderBy('group_id')->get(),'groups'=>Group::orderBy('name')->get()]);}
    public function updateGroup(Request $r,Enrollment $enrollment){$this->allowedEnrollment($r,$enrollment);$d=$r->validate(['group_id'=>'nullable|exists:groups,id']);$enrollment->update($d);return back()->with('ok','Группа слушателя изменена.');}
}
