<?php
namespace App\Console\Commands;

use App\Models\{Quiz,QuizQuestion,QuizOption};
use App\Services\LegacyQuizPackageParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyQuizBank extends Command
{
 protected $signature='legacy:import-quiz-bank {--dry-run} {--tests-dir=} {--answers-dir=}';
 protected $description='Import tm_test metadata and normalize parseable legacy test packages into Laravel quiz questions/options';

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
  $db->table('tm_test')->orderBy('num')->chunkById(100,function($rows)use($parser,$testsDir,&$done,&$parsed,&$packages,&$missing){
   foreach($rows as $r){
    $quiz=Quiz::updateOrCreate(['legacy_id'=>(int)$r->num],[
     'title'=>(string)($r->nazv?:('Тест #'.$r->num)),
     'description'=>'Импортировано из legacy tm_test',
     'legacy_path'=>$r->path??null,
     'legacy_answer_file'=>$r->tex??null,
     'legacy_image'=>$r->img??null,
     'legacy_question_count'=>isset($r->col_v)?(int)$r->col_v:null,
     'legacy_date'=>$this->date($r->dat??null),
     'is_active'=>true,
    ]);
    $path=$this->packagePath($testsDir,(string)($r->path??''));
    $result=$parser->parse($path);
    $quiz->update(['import_status'=>$result['status'],'import_message'=>$result['message']]);
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
 private function date($v){if(!$v||$v==='0000-00-00')return null;try{return \Carbon\Carbon::parse($v)->toDateString();}catch(\Throwable $e){return null;}}
}
