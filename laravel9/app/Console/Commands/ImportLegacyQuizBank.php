<?php
namespace App\Console\Commands;

use App\Models\{Quiz,QuizQuestion,QuizOption};
use App\Services\LegacyQuizPackageParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyQuizBank extends Command
{
 protected $signature='legacy:import-quiz-bank {--dry-run} {--tests-dir=} {--answers-dir=}';
 protected $description='Import tm_test metadata, archive legacy packages and normalize parseable questions/options';

 public function handle(LegacyQuizPackageParser $parser): int
 {
  $db=DB::connection('legacy');$schema=$db->getSchemaBuilder();
  if(!$schema->hasTable('tm_test')){$this->error('tm_test не найдена');return self::FAILURE;}
  $testsDir=$this->option('tests-dir')?:base_path('../testy');
  $answersDir=$this->option('answers-dir')?:base_path('../otv');
  $count=$db->table('tm_test')->count();
  $this->info("tm_test: {$count}; testy={$testsDir}; otv={$answersDir}");
  if($this->option('dry-run'))return self::SUCCESS;

  $done=$parsed=$packages=$missing=0;
  $db->table('tm_test')->orderBy('num')->chunkById(100,function($rows)use($parser,$testsDir,$answersDir,&$done,&$parsed,&$packages,&$missing){
   foreach($rows as $r){
    $legacyId=(int)$r->num;
    $quiz=Quiz::updateOrCreate(['legacy_id'=>$legacyId],[
     'title'=>(string)($r->nazv?:('Тест #'.$legacyId)),
     'description'=>'Импортировано из legacy tm_test',
     'legacy_path'=>$r->path??null,
     'legacy_answer_file'=>$r->tex??null,
     'legacy_image'=>$r->img??null,
     'legacy_question_count'=>isset($r->col_v)?(int)$r->col_v:null,
     'legacy_date'=>$this->date($r->dat??null),
     'is_active'=>true,
    ]);

    $source=$this->packagePath($testsDir,(string)($r->path??''));
    $archive=$this->archivePackage($testsDir,(string)($r->path??''),$legacyId);
    $answerArchive=$this->archiveAnswer($answersDir,(string)($r->tex??''),$legacyId);
    $result=$parser->parse($source);
    $quiz->update(['legacy_archive_path'=>$archive,'legacy_answer_archive_path'=>$answerArchive,'import_status'=>$result['status'],'import_message'=>$result['message']]);

    if($result['status']==='parsed'){
     DB::transaction(function()use($quiz,$result){
      $quiz->questions()->delete();
      foreach($result['questions'] as $qrow){
       $q=QuizQuestion::create(['quiz_id'=>$quiz->id,'legacy_id'=>null,'question'=>$qrow['question'],'type'=>$qrow['type']??'single','position'=>$qrow['position']??0,'points'=>$qrow['points']??1]);
       foreach($qrow['options']??[] as $orow)QuizOption::create(['quiz_question_id'=>$q->id,'legacy_id'=>null,'text'=>$orow['text'],'is_correct'=>(bool)($orow['is_correct']??false),'position'=>$orow['position']??0]);
      }
     });$parsed++;
    } elseif($result['status']==='package_only')$packages++; else $missing++;
    $done++;
   }
  },'num');
  $this->info("Карточек: {$done}; распознано в вопросы: {$parsed}; только legacy-пакет: {$packages}; отсутствует/ошибка: {$missing}");
  return self::SUCCESS;
 }

 private function packagePath(string $base,string $path): string
 {
  $path=ltrim(str_replace('\\','/',$path),'/');
  $full=rtrim($base,'/\\').DIRECTORY_SEPARATOR.$path;
  if(is_dir($full))$full=rtrim($full,'/\\').DIRECTORY_SEPARATOR.'index.html';
  return $full;
 }
 private function archivePackage(string $base,string $path,int $legacyId): ?string
 {
  if($path==='')return null;
  $relative=ltrim(str_replace('\\','/',$path),'/');$source=rtrim($base,'/\\').DIRECTORY_SEPARATOR.$relative;
  if(!file_exists($source))return null;
  $root=storage_path('app/legacy-quiz-bank/tests/'.$legacyId);File::ensureDirectoryExists($root);
  if(is_dir($source)){File::copyDirectory($source,$root);return 'legacy-quiz-bank/tests/'.$legacyId.'/';}
  $name=basename($source);File::copy($source,$root.DIRECTORY_SEPARATOR.$name);return 'legacy-quiz-bank/tests/'.$legacyId.'/'.$name;
 }
 private function archiveAnswer(string $base,string $name,int $legacyId): ?string
 {
  if($name==='')return null;$source=rtrim($base,'/\\').DIRECTORY_SEPARATOR.basename($name);if(!is_file($source))return null;
  $dir=storage_path('app/legacy-quiz-bank/answers');File::ensureDirectoryExists($dir);$ext=pathinfo($source,PATHINFO_EXTENSION);$target=$dir.DIRECTORY_SEPARATOR.$legacyId.($ext?'.'.$ext:'');File::copy($source,$target);return 'legacy-quiz-bank/answers/'.basename($target);
 }
 private function date($v){if(!$v||$v==='0000-00-00')return null;try{return \Carbon\Carbon::parse($v)->toDateString();}catch(\Throwable $e){return null;}}
}
