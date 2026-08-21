<?php
namespace App\Console\Commands;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\Program;
use App\Models\ProgramType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
class ImportLegacyData extends Command {
 protected $signature='legacy:import {--core : Import programs, groups and users} {--program-images-dir=}';
 protected $description='Import data from the legacy ZMUDPO database using legacy IDs';
 public function handle(): int {
   if(!$this->option('core')){$this->warn('Use --core for the first migration stage.');return self::SUCCESS;}
   $legacy=DB::connection('legacy');$schema=$legacy->getSchemaBuilder();
   $imageDir=$this->option('program-images-dir')?:base_path('../timg');File::ensureDirectoryExists(public_path('timg'));

   $this->info('Importing program types...');
   if($schema->hasTable('tm_spec_type')){
    foreach($legacy->table('tm_spec_type')->orderBy('num')->get() as $r){
     ProgramType::updateOrCreate(['legacy_id'=>(int)$r->num],['name'=>trim((string)$r->nazv)?:('Тип '.(int)$r->num)]);
    }
   }
   $types=ProgramType::whereNotNull('legacy_id')->get()->keyBy(fn($x)=>(int)$x->legacy_id);

   $this->info('Importing programs...');
   $legacy->table('tm_spec')->orderBy('num')->chunkById(200,function($rows)use($imageDir,$types){foreach($rows as $r){
    $image=isset($r->img)?basename((string)$r->img):null;if($image){$src=rtrim($imageDir,'/\\').DIRECTORY_SEPARATOR.$image;if(is_file($src))@copy($src,public_path('timg/'.$image));}
    $legacyType=(int)($r->kr??0);$type=$types->get($legacyType);$typeName=mb_strtolower((string)($type?->name??''));
    $mode=(str_contains($typeName,'непрерыв')||preg_match('/(^|\s)нмо(\s|$)/u',$typeName))?'nmo':'dpo';
    Program::updateOrCreate(['legacy_id'=>$r->num],[
     'title'=>$r->nazv ?: 'Программа '.$r->num,
     'starts_at'=>$r->dat ?: null,
     'image'=>$image ?: null,
     'is_active'=>(bool)$r->actiiv,
     'program_type_id'=>$type?->id,
     'mode'=>$mode,
     'hours'=>isset($r->chas)&&is_numeric($r->chas)?(int)$r->chas:null,
     'category'=>isset($r->kategor)&&trim((string)$r->kategor)!==''?trim((string)$r->kategor):null,
     'price'=>isset($r->cena)&&is_numeric(str_replace(',','.',(string)$r->cena))?(float)str_replace(',','.',(string)$r->cena):null,
     'registration_enabled'=>(bool)($r->zap??1),
     'featured'=>(bool)($r->gl??0),
     'is_featured_public'=>(bool)($r->gl??0),
    ]);
   }},'num');
   $this->info('Importing groups...');
   $legacy->table('tm_grupp')->orderBy('id')->chunkById(200,function($rows){foreach($rows as $r){Group::updateOrCreate(['legacy_id'=>$r->id],['name'=>$r->nazv ?: 'Группа '.$r->id]);}},'id');
   $groups=Group::whereNotNull('legacy_id')->pluck('id','legacy_id');$programs=Program::whereNotNull('legacy_id')->pluck('id','legacy_id');
   $this->info('Importing users and enrollments...');
   $legacy->table('tm_user')->orderBy('num')->chunkById(200,function($rows)use($groups,$programs){foreach($rows as $r){$password=(string)($r->passw ?? '');$user=User::updateOrCreate(['legacy_id'=>$r->num],['login'=>(string)$r->num,'full_name'=>$r->fio ?: 'Слушатель '.$r->num,'password'=>Hash::make($password !== '' ? $password : bin2hex(random_bytes(16))),'role'=>'student','is_active'=>(bool)($r->act ?? 0),'is_legal_entity'=>(bool)($r->urlico ?? 0)]);$programId=$programs[(int)($r->spec ?? 0)]??null;if($programId){Enrollment::updateOrCreate(['user_id'=>$user->id,'program_id'=>$programId],['group_id'=>$groups[(int)($r->grupp ?? 0)]??null,'legacy_user_id'=>$r->num,'status'=>(bool)($r->act ?? 0)?'active':'inactive']);}}},'num');
   $this->newLine();$this->info('Core legacy import completed. Program types were restored from tm_spec_type; legacy plaintext passwords were hashed during import.');return self::SUCCESS; }
}
