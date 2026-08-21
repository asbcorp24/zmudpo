<?php
namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\{Announcement,Enrollment,LegacyCuratorRecord};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ArchiveController extends Controller
{
 private function enrollments(Request $r){$q=Enrollment::with(['user','program','group'])->whereIn('status',['active','completed']);if(!$r->user()->isAdmin())$q->where('curator_id',$r->user()->id);return $q;}
 public function logins(Request $r)
 {
  $enrollments=$this->enrollments($r)->get();$ids=$enrollments->pluck('user_id');
  $items=Schema::hasTable('login_activities')?DB::table('login_activities')->whereIn('user_id',$ids)->latest('logged_in_at')->paginate(100):null;
  $legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::with('user')->where('type','login')->whereIn('user_id',$ids)->latest('occurred_at')->paginate(100,['*'],'legacy_page'):null;
  return view('curator.logins',compact('enrollments','items','legacy'));
 }
 public function announcements(Request $r)
 {
  $enrollments=$this->enrollments($r)->get();$pids=$enrollments->pluck('program_id')->unique();$groups=$enrollments->pluck('group','group_id')->filter();
  $items=Announcement::with(['program','group','author'])->where(fn($q)=>$q->whereNull('program_id')->orWhereIn('program_id',$pids))->latest('published_at')->paginate(50);
  $legacy=Schema::hasTable('legacy_curator_records')?LegacyCuratorRecord::where('type','announcement')->latest('occurred_at')->paginate(100,['*'],'legacy_page'):null;
  return view('curator.announcements',compact('items','legacy','groups')+['programs'=>$enrollments->pluck('program','program_id')->filter()]);
 }
 public function storeAnnouncement(Request $r)
 {
  $enrollments=$this->enrollments($r)->get();$pids=$enrollments->pluck('program_id')->unique();$groupIds=$enrollments->pluck('group_id')->filter()->unique();
  $d=$r->validate(['title'=>'required|string|max:255','body'=>'required|string','program_id'=>'required|exists:programs,id','group_id'=>'nullable|exists:groups,id','published_at'=>'nullable|date','expires_at'=>'nullable|date|after_or_equal:published_at']);
  abort_unless($r->user()->isAdmin()||$pids->contains((int)$d['program_id']),403);if(!empty($d['group_id']))abort_unless($r->user()->isAdmin()||$groupIds->contains((int)$d['group_id']),403);
  Announcement::create($d+['author_id'=>$r->user()->id,'is_active'=>true,'published_at'=>$d['published_at']??now()]);return back()->with('ok','Объявление опубликовано.');
 }
}
