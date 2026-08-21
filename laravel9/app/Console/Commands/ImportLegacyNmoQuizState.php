<?php
namespace App\Console\Commands;

use App\Models\{LearningSection,LearningSectionProgress,LegacyQuizXmlResult,QuizAssignment,QuizUserOverride,SectionProgress,User};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyNmoQuizState extends Command
{
 protected $signature='legacy:import-nmo-quiz-state {--dry-run} {--xml-dir=}';
 protected $description='Import NMO test activation, section progress and legacy XML result archives';
 public function handle(): int {
  $db=DB::connection('legacy');$schema=$db->getSchemaBuilder();
  foreach(['tm_nmo_razd_media_user_act_test','tm_nmo_razd_user','tm_nmo_razd_media'] as $t)$this->line(($schema->hasTable($t)?'[+] ':'[-] ').$t);
  $xmlDir=$this->option('xml-dir')?:base_path('../userxml');$this->line((is_dir($xmlDir)?'[+] ':'[-] ').'XML: '.$xmlDir);
  if($this->option('dry-run'))return self::SUCCESS;
  $users=User::whereNotNull('legacy_id')->pluck('id','legacy_id');$sections=LearningSection::whereNotNull('legacy_id')->get()->keyBy('legacy_id');
  if($schema->hasTable('tm_nmo_razd_user'))$this->importProgress($db,$users,$sections);
  if($schema->hasTable('tm_nmo_razd_media_user_act_test')&&$schema->hasTable('tm_nmo_razd_media'))$this->importActivations($db,$users,$sections);
  if(is_dir($xmlDir))$this->importXml($xmlDir);
  return self::SUCCESS;
 }
 private function importProgress($db,$users,$sections): void {
  $done=$skip=$synced=0;
  $db->table('tm_nmo_razd_user')->orderBy('id')->chunkById(500,function($rows)use($users,$sections,&$done,&$skip,&$synced){
   foreach($rows as $r){
    $uid=$users[(int)($r->user??0)]??null;$section=$sections[(int)($r->razdel??0)]??null;
    if(!$uid){$skip++;continue;}
    $completed=(bool)($r->proydeno??0);$legacyDate=$this->date($r->dat??null);
    LearningSectionProgress::updateOrCreate(['legacy_id'=>(int)$r->id],[
     'user_id'=>$uid,'learning_section_id'=>$section?->id,'program_id'=>$section?->program_id,
     'legacy_section_id'=>(int)($r->razdel??0),'completed'=>$completed,
     'sp'=>isset($r->sp)?(int)$r->sp:null,'psp'=>isset($r->psp)?(int)$r->psp:null,'pop'=>isset($r->pop)?(string)$r->pop:null,
     'legacy_date'=>$legacyDate,'legacy_file'=>$r->dop_file??null,'extra'=>['dop'=>$r->dop??null]
    ]);
    // The modern student cabinet, prerequisites and CompletionService use section_progress.
    // Mirror mapped NMO state there so migrated users do not lose already completed sections.
    if($section){
     $current=SectionProgress::firstOrNew(['user_id'=>$uid,'learning_section_id'=>$section->id]);
     if($completed){
      $current->progress_percent=100;
      if(!$current->completed_at)$current->completed_at=$legacyDate?:now();
     } elseif(!$current->exists && $current->progress_percent===null){
      $current->progress_percent=0;
     }
     $meta=(array)($current->meta??[]);$meta['legacy_nmo_progress_id']=(int)$r->id;$meta['legacy_nmo']=true;$current->meta=$meta;$current->save();$synced++;
    }
    $done++;
   }
  },'id');
  $this->info("Состояния разделов НМО: {$done}; синхронизировано с кабинетом: {$synced}; пропущено: {$skip}");
 }
 private function importActivations($db,$users,$sections): void {
  $media=$db->table('tm_nmo_razd_media')->where('tip',3)->get()->keyBy('id');$assignments=QuizAssignment::with('quiz')->get();$done=$skip=0;
  $db->table('tm_nmo_razd_media_user_act_test')->orderBy('id')->chunkById(500,function($rows)use($users,$sections,$media,$assignments,&$done,&$skip){foreach($rows as $r){$uid=$users[(int)($r->user??0)]??null;$m=$media[(int)($r->razd_media_test??0)]??null;if(!$uid||!$m){$skip++;continue;}$section=$sections[(int)($m->tm_nmo_razd??0)]??null;$candidates=$assignments->where('program_id',$section?->program_id);if($section)$candidates=$candidates->filter(fn($a)=>!$a->learning_section_id||$a->learning_section_id===$section->id);$legacyQuiz=$this->numericQuizId($m->path??null);if($legacyQuiz)$candidates=$candidates->filter(fn($a)=>$a->quiz?->legacy_id==$legacyQuiz);if($candidates->count()!==1){$skip++;continue;}$a=$candidates->first();if(!$a->legacy_media_id)$a->update(['legacy_media_id'=>(int)$m->id]);QuizUserOverride::updateOrCreate(['quiz_assignment_id'=>$a->id,'user_id'=>$uid],['quiz_id'=>$a->quiz_id,'legacy_id'=>(int)$r->id,'available_from'=>$this->date($r->datact??null),'is_active_override'=>isset($r->act)?(bool)$r->act:null,'reason'=>'Импортировано из tm_nmo_razd_media_user_act_test']);$done++;}},'id');$this->info("Персональные активации NMO-тестов: {$done}; неоднозначно/пропущено: {$skip}");
 }
 private function importXml(string $dir): void {
  $done=$bad=0;foreach(glob(rtrim($dir,'/\\').'/*.xml')?:[] as $file){$raw=@file_get_contents($file);if($raw===false)continue;libxml_use_internal_errors(true);$xml=simplexml_load_string($raw);if(!$xml){$bad++;continue;}$questions=[];$correct=0;if(isset($xml->questions)){foreach($xml->questions->children() as $q){$status=((string)$q['status'])==='correct';if($status)$correct++;$answers=[];foreach($q->answers->answer??[] as $a)$answers[]=(string)$a;$questions[]=['status'=>$status,'question'=>(string)$q->direction,'user_answer_index'=>(string)($q->answers['userAnswerIndex']??''),'correct_answer_index'=>(string)($q->answers['correctAnswerIndex']??''),'answers'=>$answers];}}LegacyQuizXmlResult::updateOrCreate(['filename'=>basename($file)],['checksum'=>hash('sha256',$raw),'question_count'=>count($questions),'correct_count'=>$correct,'questions'=>$questions,'raw_xml'=>$raw,'meta'=>['imported_from'=>$file]]);$done++;}libxml_clear_errors();$this->info("XML-архив результатов: {$done}; повреждено/не разобрано: {$bad}");
 }
 private function numericQuizId($path): ?int {if(!$path)return null;$s=basename((string)$path);return preg_match('/(\d+)/',$s,$m)?(int)$m[1]:null;}
 private function date($v){if(!$v||$v==='0000-00-00'||$v==='0000-00-00 00:00:00')return null;try{return \Carbon\Carbon::parse($v);}catch(\Throwable $e){return null;}}
}
