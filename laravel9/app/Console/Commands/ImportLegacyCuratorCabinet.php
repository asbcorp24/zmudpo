<?php
namespace App\Console\Commands;

use App\Models\{LegacyCuratorRecord,User};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportLegacyCuratorCabinet extends Command
{
 protected $signature='legacy:import-curator {--dry-run} {--workbooks-dir=} {--user-files-dir=}';
 protected $description='Import historical curator cabinet data: attendance, practices, workbooks, files, chat and announcements';
 public function handle(): int
 {
  $db=DB::connection('legacy');$s=$db->getSchemaBuilder();$tables=['nmo_otm_pos','tm_nmo_pract','tm_user_sh','tm_nmo_user_file','tm_chat_kurator','tm_obiav'];
  foreach($tables as $t)$this->line(($s->hasTable($t)?'[+] ':'[-] ').$t);if($this->option('dry-run'))return self::SUCCESS;
  $users=User::whereNotNull('legacy_id')->pluck('id','legacy_id');
  if($s->hasTable('nmo_otm_pos'))$this->attendance($db,$users);if($s->hasTable('tm_nmo_pract'))$this->practice($db,$users);if($s->hasTable('tm_user_sh'))$this->workbooks($db,$users,$this->option('workbooks-dir')?:base_path('../tetrad/user'));if($s->hasTable('tm_nmo_user_file'))$this->files($db,$users,$this->option('user-files-dir')?:base_path('../usrimg'));if($s->hasTable('tm_chat_kurator'))$this->chat($db,$users);if($s->hasTable('tm_obiav'))$this->announcements($db);return self::SUCCESS;
 }
 private function attendance($db,$users): void {$n=0;foreach($db->table('nmo_otm_pos')->get() as $r){$legacy=(int)($r->user??0);LegacyCuratorRecord::updateOrCreate(['type'=>'attendance','source_key'=>$this->key($r,['id','num'])],['user_id'=>$users[$legacy]??null,'legacy_user_id'=>$legacy?:null,'legacy_scope_id'=>(int)($r->razdel??0)?:null,'occurred_at'=>$this->date($r->dat??null),'meta'=>(array)$r]);$n++;}$this->info("Посещаемость: {$n}");}
 private function practice($db,$users): void {$n=0;foreach($db->table('tm_nmo_pract')->get() as $r){$raw=(int)($r->user??0);$legacy=($raw<0&&isset($r->old))?(int)$r->old:$raw;LegacyCuratorRecord::updateOrCreate(['type'=>'practice','source_key'=>$this->key($r,['id','num'])],['user_id'=>$users[$legacy]??null,'legacy_user_id'=>$legacy?:null,'legacy_scope_id'=>(int)($r->razdel??0)?:null,'occurred_at'=>$this->date($r->dat??null),'body'=>$r->chto_del??null,'response'=>$r->otvets??null,'meta'=>(array)$r]);$n++;}$this->info("Практики: {$n}");}
 private function workbooks($db,$users,string $dir): void {$n=$copied=0;foreach($db->table('tm_user_sh')->get() as $r){$legacy=(int)($r->user??0);$path=(string)($r->path??'');$key=$this->key($r,['id','num']);$archive=null;if($path!==''){$src=rtrim($dir,'/\\').DIRECTORY_SEPARATOR.$path.(str_ends_with(strtolower($path),'.zip')?'':'.zip');if(is_file($src)){$archive='legacy-curator/workbooks/'.$legacy.'-'.$key.'.zip';Storage::disk('local')->put($archive,file_get_contents($src));$copied++;}}LegacyCuratorRecord::updateOrCreate(['type'=>'workbook','source_key'=>$key],['user_id'=>$users[$legacy]??null,'legacy_user_id'=>$legacy?:null,'legacy_scope_id'=>(int)($r->media??0)?:null,'path'=>$path?:null,'archive_path'=>$archive,'meta'=>(array)$r]);$n++;}$this->info("Рабочие тетради: {$n}; архивов скопировано: {$copied}");}
 private function files($db,$users,string $dir): void {$n=$copied=0;foreach($db->table('tm_nmo_user_file')->get() as $r){$legacy=(int)($r->user??0);$path=(string)($r->path??'');$key=$this->key($r,['num','id']);$archive=null;if($path!==''&&!preg_match('~^https?://~i',$path)){$src=rtrim($dir,'/\\').DIRECTORY_SEPARATOR.$path;if(is_file($src)){$archive='legacy-curator/user-files/'.$legacy.'-'.$key.'-'.basename($src);Storage::disk('local')->put($archive,file_get_contents($src));$copied++;}}LegacyCuratorRecord::updateOrCreate(['type'=>'user_file','source_key'=>$key],['user_id'=>$users[$legacy]??null,'legacy_user_id'=>$legacy?:null,'legacy_scope_id'=>(int)($r->inn??0)?:null,'occurred_at'=>$this->date($r->dat??null),'body'=>$r->comment??null,'path'=>$path?:null,'archive_path'=>$archive,'meta'=>(array)$r]);$n++;}$this->info("Файлы студентов: {$n}; файлов скопировано: {$copied}");}
 private function chat($db,$users): void {$n=0;foreach($db->table('tm_chat_kurator')->get() as $r){$legacy=(int)($r->user??0);LegacyCuratorRecord::updateOrCreate(['type'=>'chat','source_key'=>$this->key($r,['num','id'])],['user_id'=>$users[$legacy]??null,'legacy_user_id'=>$legacy?:null,'legacy_scope_id'=>(int)($r->razdel??0)?:null,'occurred_at'=>$this->date($r->dat??null),'body'=>$r->txt??null,'meta'=>(array)$r]);$n++;}$this->info("Сообщения куратора: {$n}");}
 private function announcements($db): void {$n=0;foreach($db->table('tm_obiav')->get() as $r){LegacyCuratorRecord::updateOrCreate(['type'=>'announcement','source_key'=>$this->key($r,['id','num'])],['legacy_scope_id'=>(int)($r->spec??0)?:null,'occurred_at'=>$this->date($r->dat??null),'body'=>$r->tex??null,'meta'=>(array)$r]);$n++;}$this->info("Объявления: {$n}");}
 private function key($r,array $ids): string {foreach($ids as $id)if(isset($r->$id)&&$r->$id!=='')return (string)$r->$id;return sha1(json_encode($r,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));}
 private function date($v){if(!$v||$v==='0000-00-00'||$v==='0000-00-00 00:00:00')return null;try{return \Carbon\Carbon::parse($v);}catch(\Throwable $e){return null;}}
}
