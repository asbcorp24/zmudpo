<?php
namespace App\Console\Commands;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\Program;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class ImportLegacyData extends Command {
 protected $signature='legacy:import {--core : Import programs, groups and users}';
 protected $description='Import data from the legacy ZMUDPO database using legacy IDs';
 public function handle(): int {
   if(!$this->option('core')){$this->warn('Use --core for the first migration stage.');return self::SUCCESS;}
   $this->info('Importing programs...');
   DB::connection('legacy')->table('tm_spec')->orderBy('num')->chunkById(200,function($rows){foreach($rows as $r){Program::updateOrCreate(['legacy_id'=>$r->num],[
    'title'=>$r->nazv ?: 'Программа '.$r->num,
    'starts_at'=>$r->dat ?: null,
    'image'=>$r->img ?: null,
    'is_active'=>(bool)$r->actiiv,
    'mode'=>isset($r->kr)&&((int)$r->kr!==0)?'nmo':'dpo',
    'hours'=>isset($r->chas)&&is_numeric($r->chas)?(int)$r->chas:null,
    'category'=>isset($r->kategor)&&trim((string)$r->kategor)!==''?trim((string)$r->kategor):null,
    'price'=>isset($r->cena)&&is_numeric(str_replace(',','.',(string)$r->cena))?(float)str_replace(',','.',(string)$r->cena):null,
    'registration_enabled'=>(bool)($r->zap??1),
    'featured'=>(bool)($r->gl??0),
    'is_featured_public'=>(bool)($r->gl??0),
   ]);}},'num');
   $this->info('Importing groups...');
   DB::connection('legacy')->table('tm_grupp')->orderBy('id')->chunkById(200,function($rows){foreach($rows as $r){Group::updateOrCreate(['legacy_id'=>$r->id],['name'=>$r->nazv ?: 'Группа '.$r->id]);}},'id');
   $groups=Group::whereNotNull('legacy_id')->pluck('id','legacy_id');$programs=Program::whereNotNull('legacy_id')->pluck('id','legacy_id');
   $this->info('Importing users and enrollments...');
   DB::connection('legacy')->table('tm_user')->orderBy('num')->chunkById(200,function($rows)use($groups,$programs){foreach($rows as $r){$password=(string)($r->passw ?? '');$user=User::updateOrCreate(['legacy_id'=>$r->num],['login'=>(string)$r->num,'full_name'=>$r->fio ?: 'Слушатель '.$r->num,'password'=>Hash::make($password !== '' ? $password : bin2hex(random_bytes(16))),'role'=>'student','is_active'=>(bool)($r->act ?? 0),'is_legal_entity'=>(bool)($r->urlico ?? 0)]);$programId=$programs[(int)($r->spec ?? 0)]??null;if($programId){Enrollment::updateOrCreate(['user_id'=>$user->id,'program_id'=>$programId],['group_id'=>$groups[(int)($r->grupp ?? 0)]??null,'legacy_user_id'=>$r->num,'status'=>(bool)($r->act ?? 0)?'active':'inactive']);}}},'num');
   $this->newLine();$this->info('Core legacy import completed. Legacy plaintext passwords were hashed during import.');return self::SUCCESS; }
}
