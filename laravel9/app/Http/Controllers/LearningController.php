<?php
namespace App\Http\Controllers;
use App\Models\{Announcement,AnnouncementUserAcknowledgement,ContentItem,ContentItemEntitlement,ContentItemResponse,ContentItemTicketAssignment,Enrollment,InstructorProgram,LearningSection,LearningSectionVisit,NmoPracticeDiaryEntry,SectionProgress,QuizAssignment,PracticeAssignment,QuizAttempt,Submission};
use App\Services\{CompletionService,LearningAccessService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Storage};
class LearningController extends Controller {
 public function index(Request $request,LearningAccessService $access){
  $programIds=$request->user()->enrollments()->where('status','active')->pluck('program_id');
  $sections=LearningSection::with('program')->whereIn('program_id',$programIds)->where('is_active',true)->orderBy('program_id')->orderBy('position')->get();
  $progress=SectionProgress::where('user_id',$request->user()->id)->whereIn('learning_section_id',$sections->pluck('id'))->get()->keyBy('learning_section_id');
  foreach($sections as $section)$section->setAttribute('can_open',$access->canOpenSection($request->user(),$section));
  return view('learning.index',compact('sections','progress'));
 }
 public function show(Request $request,LearningSection $section,LearningAccessService $access){
  abort_unless($access->canOpenSection($request->user(),$section),403,'Раздел пока недоступен.');
  $section->load('program');
  $section->load(['contentItems'=>fn($q)=>$q
   ->where('is_active',true)
   ->where(function($x){$x->whereNull('available_from')->orWhere('available_from','<=',now());})
   ->where(function($x){$x->whereNull('available_until')->orWhere('available_until','>=',now());})
   ->orderBy('position')]);
  $progress=SectionProgress::firstOrCreate(['user_id'=>$request->user()->id,'learning_section_id'=>$section->id],['progress_percent'=>0]);
  $quizzes=QuizAssignment::with('quiz')->where('program_id',$section->program_id)->where('learning_section_id',$section->id)->where('is_active',true)->get();
  $quizAttempts=QuizAttempt::where('user_id',$request->user()->id)->whereIn('quiz_assignment_id',$quizzes->pluck('id'))->latest('finished_at')->get()->groupBy('quiz_assignment_id');
  $practice=PracticeAssignment::where('program_id',$section->program_id)->where('learning_section_id',$section->id)->where('is_active',true)->get();
  $submissions=Submission::where('user_id',$request->user()->id)->whereIn('practice_assignment_id',$practice->pluck('id'))->get()->keyBy('practice_assignment_id');
  $responses=ContentItemResponse::where('user_id',$request->user()->id)->whereIn('content_item_id',$section->contentItems->pluck('id'))->get()->keyBy('content_item_id');
  $paymentItems=ContentItem::whereHas('section',fn($q)=>$q->where('program_id',$section->program_id))->where('legacy_type',11)->where('is_active',true)->get();
  $paymentTargets=collect();foreach($paymentItems as $pay){foreach((array)data_get($pay->settings,'unlock_item_ids',[]) as $targetId)$paymentTargets->put((int)$targetId,$pay);}
  $entitledItemIds=ContentItemEntitlement::where('user_id',$request->user()->id)->whereIn('content_item_id',$paymentTargets->keys())->pluck('content_item_id');

  LearningSectionVisit::create(['learning_section_id'=>$section->id,'user_id'=>$request->user()->id,'visited_at'=>now()]);
  $enrollment=Enrollment::with('group')->where('user_id',$request->user()->id)->where('program_id',$section->program_id)->where('status','active')->first();
  $announcements=Announcement::where('is_active',true)
   ->where(fn($q)=>$q->whereNull('published_at')->orWhere('published_at','<=',now()))
   ->where(fn($q)=>$q->whereNull('expires_at')->orWhere('expires_at','>=',now()))
   ->where(fn($q)=>$q->whereNull('program_id')->orWhere('program_id',$section->program_id))
   ->where(fn($q)=>$q->whereNull('group_id')->orWhere('group_id',$enrollment?->group_id))
   ->latest('published_at')->get();
  $acknowledged=AnnouncementUserAcknowledgement::where('user_id',$request->user()->id)->whereIn('announcement_id',$announcements->pluck('id'))->pluck('announcement_id');
  $instructorAssignments=InstructorProgram::with(['instructor','slots'=>fn($q)=>$q->whereDate('date','>=',now()->toDateString())->orderBy('date')->orderBy('time')])
   ->where('program_id',$section->program_id)->get();
  $slotIds=$instructorAssignments->flatMap(fn($a)=>$a->slots->pluck('id'));
  $bookedSlotIds=$slotIds->isEmpty()?collect():DB::table('instructor_slot_user')->where('user_id',$request->user()->id)->whereIn('slot_id',$slotIds)->pluck('slot_id');
  $slotCounts=$slotIds->isEmpty()?collect():DB::table('instructor_slot_user')->selectRaw('slot_id,count(*) cnt')->whereIn('slot_id',$slotIds)->groupBy('slot_id')->pluck('cnt','slot_id');

  return view('learning.show',compact('section','progress','quizzes','quizAttempts','practice','submissions','responses','paymentTargets','entitledItemIds','announcements','acknowledged','instructorAssignments','bookedSlotIds','slotCounts','enrollment'));
 }

 public function acknowledgeAnnouncement(Request $request,Announcement $announcement){
  $programIds=$request->user()->enrollments()->whereIn('status',['active','completed'])->pluck('program_id');
  abort_unless(!$announcement->program_id || $programIds->contains($announcement->program_id),403);
  AnnouncementUserAcknowledgement::updateOrCreate(
   ['announcement_id'=>$announcement->id,'user_id'=>$request->user()->id],
   ['acknowledged_at'=>now()]
  );
  return back()->with('ok','Объявление отмечено как прочитанное.');
 }

 public function acceptPersonalData(Request $request){
  if(!$request->user()->personal_data_consent_at)$request->user()->update(['personal_data_consent_at'=>now()]);
  return back()->with('ok','Согласие сохранено.');
 }

 public function complete(Request $request,LearningSection $section,LearningAccessService $access){
  abort_unless($access->canOpenSection($request->user(),$section),403);
  $requiredItems=ContentItem::where('learning_section_id',$section->id)->where('is_active',1)->where('is_required',1)->get();
  foreach($requiredItems as $item){
   $legacy=(int)$item->legacy_type;
   if(in_array($legacy,[4,10,17],true)){
    $ok=ContentItemResponse::where('content_item_id',$item->id)->where('user_id',$request->user()->id)->where('status','accepted')->exists();
    abort_unless($ok,422,'Не приняты все обязательные работы раздела.');
   }
   if(in_array($legacy,[3,15,18,21,22],true) && data_get($item->settings,'quiz_assignment_id')){
    $ok=QuizAttempt::where('quiz_assignment_id',data_get($item->settings,'quiz_assignment_id'))->where('user_id',$request->user()->id)->where('passed',1)->exists();
    abort_unless($ok,422,'Не пройдены все обязательные тесты раздела.');
   }
  }
  $practiceIds=PracticeAssignment::where('learning_section_id',$section->id)->where('is_active',1)->pluck('id');
  if($practiceIds->isNotEmpty()){
   $accepted=Submission::where('user_id',$request->user()->id)->whereIn('practice_assignment_id',$practiceIds)->whereIn('status',['accepted','passed'])->distinct('practice_assignment_id')->count('practice_assignment_id');
   abort_unless($accepted===$practiceIds->count(),422,'Не приняты все практические задания раздела.');
  }
  SectionProgress::updateOrCreate(['user_id'=>$request->user()->id,'learning_section_id'=>$section->id],['progress_percent'=>100,'completed_at'=>now()]);
  return back()->with('success','Раздел отмечен как пройденный.');
 }
 public function download(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless($item->file_path && Storage::exists($item->file_path),404);
  $ext=strtolower(pathinfo($item->file_path,PATHINFO_EXTENSION));
  if(in_array($ext,['html','htm'],true))return response(Storage::get($item->file_path),200,['Content-Type'=>'text/html; charset=UTF-8','X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; img-src 'self' data: blob:; media-src 'self' data: blob:;"]);
  return Storage::download($item->file_path);
 }
 public function package(Request $request,ContentItem $item,LearningAccessService $access,?string $path=null){
  $item->load('section');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  $settings=$item->settings?:[];$root=$settings['package_root']??null;abort_unless($root,404);
  $relative=$path?:ltrim(str_replace($root.'/','',(string)$item->file_path),'/');
  $relative=str_replace('\\','/',$relative);abort_if(str_starts_with($relative,'/')||preg_match('/(^|\/)\.\.(\/|$)/',$relative),400);
  $full=rtrim($root,'/').'/'.ltrim($relative,'/');abort_unless(Storage::exists($full),404);
  $ext=strtolower(pathinfo($full,PATHINFO_EXTENSION));$mime=match($ext){'html','htm'=>'text/html; charset=UTF-8','js'=>'application/javascript','css'=>'text/css','json'=>'application/json','svg'=>'image/svg+xml','png'=>'image/png','jpg','jpeg'=>'image/jpeg','gif'=>'image/gif','webp'=>'image/webp','mp3'=>'audio/mpeg','mp4'=>'video/mp4','pdf'=>'application/pdf',default=>'application/octet-stream'};
  return response(Storage::get($full),200,['Content-Type'=>$mime,'X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; img-src 'self' data: blob:; media-src 'self' data: blob:;"]);
 }




 public function unlock(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);
  $settings=$item->settings?:[];
  if(empty($settings['password_required']))return back();
  $data=$request->validate(['code'=>['required','string','max:255']]);
  $codes=array_values(array_filter((array)($settings['access_codes']??[]),fn($v)=>trim((string)$v)!==''));
  $needle=trim($data['code']);$index=array_search($needle,$codes,true);
  if($index===false)return back()->withErrors(['code'=>'Код доступа неверный или уже использован.']);
  array_splice($codes,$index,1);$settings['access_codes']=$codes;$item->settings=$settings;$item->save();
  $request->session()->put('content_unlocked.'.$item->id,true);
  return back()->with('ok','Материал разблокирован.');
 }



 public function media(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===2 && $item->file_path && Storage::exists($item->file_path),404);
  $ext=strtolower(pathinfo($item->file_path,PATHINFO_EXTENSION));
  $mime=match($ext){'mp4'=>'video/mp4','webm'=>'video/webm','ogg','ogv'=>'video/ogg','mov'=>'video/quicktime',default=>'application/octet-stream'};
  return response(Storage::get($item->file_path),200,['Content-Type'=>$mime,'Content-Disposition'=>'inline']);
 }

 public function practiceDiary(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section.program');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===16,404);
  $entries=NmoPracticeDiaryEntry::where('content_item_id',$item->id)->where('user_id',$request->user()->id)->orderBy('work_date')->orderBy('id')->get();
  return view('learning.practice-diary',compact('item','entries'));
 }

 public function storePracticeDiary(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section.program');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===16,404);
  $d=$request->validate(['work_date'=>'required|date','work_done'=>'required|string|max:50000','supervisor'=>'nullable|string|max:255']);
  NmoPracticeDiaryEntry::create($d+['content_item_id'=>$item->id,'user_id'=>$request->user()->id]);
  return back()->with('ok','Запись дневника добавлена.');
 }

 public function updatePracticeDiary(Request $request,NmoPracticeDiaryEntry $entry){
  abort_unless($entry->user_id===$request->user()->id,403);
  $d=$request->validate(['work_date'=>'required|date','work_done'=>'required|string|max:50000','supervisor'=>'nullable|string|max:255']);
  $entry->update($d);
  return back()->with('ok','Запись дневника сохранена.');
 }

 public function certificate(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section.program');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===15,404);
  $assignmentId=(int)data_get($item->settings,'quiz_assignment_id');
  abort_unless($assignmentId,422,'Для сертификата не назначен тест.');
  $attempt=QuizAttempt::where('quiz_assignment_id',$assignmentId)->where('user_id',$request->user()->id)->where('passed',1)->latest('finished_at')->first();
  abort_unless($attempt,403,'Сертификат доступен после успешного прохождения теста.');
  return view('learning.certificate',compact('item','attempt'));
 }

 public function table(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section.program');$this->authorizeItem($request,$item,$access);$this->assertPaid($request,$item);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===19 && $item->file_path && Storage::exists($item->file_path),404);
  $raw=Storage::get($item->file_path);$rows=[];
  foreach(preg_split('/\R/u',$raw) as $line){if(trim($line)==='')continue;$rows[]=str_getcsv($line,';');}
  if($rows && count($rows[0])===1){$rows=[];foreach(preg_split('/\R/u',$raw) as $line){if(trim($line)==='')continue;$rows[]=str_getcsv($line,',');}}
  return view('learning.table',compact('item','rows'));
 }

 public function completeProgram(Request $request,ContentItem $item,LearningAccessService $access,CompletionService $completion){
  $item->load('section');$this->authorizeItem($request,$item,$access);$this->assertUnlocked($request,$item);
  abort_unless((int)$item->legacy_type===5,422,'Этот элемент не завершает обучение.');
  $enrollment=Enrollment::where('user_id',$request->user()->id)->where('program_id',$item->section->program_id)->where('status','active')->firstOrFail();
  $enrollment->load('program');
  $completion->complete($enrollment);
  return redirect()->route('programs.show',$enrollment)->with('ok','Программа успешно завершена.');
 }

 public function submitResponse(Request $request,ContentItem $item,LearningAccessService $access){
  $item->load('section');$this->authorizeItem($request,$item,$access);$this->assertUnlocked($request,$item);
  $legacy=(int)$item->legacy_type;
  abort_unless(in_array($legacy,[4,10,17,20],true),422,'Этот тип материала не принимает ответ.');
  $data=$request->validate([
   'text'=>['nullable','string','max:50000'],
   'file'=>['nullable','file','max:51200'],
   'files'=>['nullable','array','max:12'],
   'files.*'=>['file','max:51200'],
   'action'=>['nullable','string','max:30'],
  ]);
  $response=ContentItemResponse::firstOrNew(['content_item_id'=>$item->id,'user_id'=>$request->user()->id]);
  $meta=$response->meta?:[];
  if($legacy===20){
   $set=$item->settings?:[];$min=(int)($set['random_min']??1);$max=(int)($set['random_max']??100);if($max<$min){$t=$min;$min=$max;$max=$t;}
   $enrollment=Enrollment::where('user_id',$request->user()->id)->where('program_id',$item->section->program_id)->where('status','active')->firstOrFail();
   $scopeKey=$enrollment->group_id?'group:'.$enrollment->group_id:'program:'.$enrollment->program_id;
   $ticket=DB::transaction(function()use($request,$item,$scopeKey,$min,$max){
    $existing=ContentItemTicketAssignment::where('content_item_id',$item->id)->where('user_id',$request->user()->id)->lockForUpdate()->first();
    if($existing)return $existing;
    $used=ContentItemTicketAssignment::where('content_item_id',$item->id)->where('scope_key',$scopeKey)->lockForUpdate()->pluck('ticket_number')->all();
    $available=array_values(array_diff(range($min,$max),$used));
    abort_if(!$available,422,'Свободных билетов больше нет. Обратитесь к куратору.');
    $number=$available[array_rand($available)];
    return ContentItemTicketAssignment::create(['content_item_id'=>$item->id,'user_id'=>$request->user()->id,'scope_key'=>$scopeKey,'ticket_number'=>$number]);
   });
   $meta['random_value']=$ticket->ticket_number;$meta['generated_at']=$ticket->created_at?->toIso8601String();
   $response->meta=$meta;$response->status='submitted';$response->submitted_at=now();$response->save();
   return back()->with('ok','Билет закреплён за вами: '.$ticket->ticket_number);
  }
  if($request->hasFile('file')){
   if($response->file_path)Storage::delete($response->file_path);
   $response->file_path=$request->file('file')->store('content-responses/'.$request->user()->id.'/'.$item->id);
  }
  if($request->hasFile('files')){
   foreach((array)$response->files as $old)if(is_string($old))Storage::delete($old);
   $paths=[];foreach($request->file('files',[]) as $file)$paths[]=$file->store('content-responses/'.$request->user()->id.'/'.$item->id);
   $response->files=$paths;
  }
  $response->text=$data['text']??$response->text;
  $response->status='submitted';$response->submitted_at=now();$response->reviewed_by=null;$response->reviewed_at=null;$response->review_comment=null;$response->save();
  return back()->with('ok','Ответ сохранён и отправлен куратору.');
 }

 public function downloadResponse(Request $request,ContentItemResponse $response,int $index=-1){
  $allowed=$response->user_id===$request->user()->id || $request->user()->isAdmin();
  if(!$allowed && $request->user()->isCurator()){
   $response->loadMissing('item.section');
   $programId=$response->item?->section?->program_id;
   $allowed=$programId && Enrollment::where('user_id',$response->user_id)->where('program_id',$programId)->where('curator_id',$request->user()->id)->exists();
  }
  abort_unless($allowed,403);
  $path=$index>=0?($response->files[$index]??null):$response->file_path;abort_unless($path&&Storage::exists($path),404);
  return Storage::download($path);
 }



 private function assertPaid(Request $request,ContentItem $item): void{
  $item->loadMissing('section');
  if(!$item->section)return;
  $pay=ContentItem::whereHas('section',fn($q)=>$q->where('program_id',$item->section->program_id))
   ->where('legacy_type',11)->where('is_active',true)->whereJsonContains('settings->unlock_item_ids',$item->id)->first();
  if($pay && !ContentItemEntitlement::where('user_id',$request->user()->id)->where('content_item_id',$item->id)->exists())abort(402,'Материал доступен после оплаты.');
 }

 private function assertUnlocked(Request $request,ContentItem $item): void{
  $settings=$item->settings?:[];
  if(!empty($settings['password_required']) && !$request->session()->get('content_unlocked.'.$item->id))abort(403,'Материал защищён одноразовым кодом доступа.');
 }

 private function authorizeItem(Request $request,ContentItem $item,LearningAccessService $access):void{
  $section=$item->section;abort_unless($section&&$item->is_active&&$access->canOpenSection($request->user(),$section),403);
  if($item->available_from&&now()->lt($item->available_from))abort(403,'Материал ещё не открыт.');
  if($item->available_until&&now()->gt($item->available_until))abort(403,'Срок доступа к материалу завершён.');
 }
}
