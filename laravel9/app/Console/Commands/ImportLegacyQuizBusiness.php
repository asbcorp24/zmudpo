<?php
namespace App\Console\Commands;

use App\Models\{Quiz,QuizAssignment,Program,User,QuizAttempt};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyQuizBusiness extends Command
{
    protected $signature='legacy:import-quiz-business {--dry-run}';
    protected $description='Import legacy test assignments/results using already imported quiz bank metadata';

    public function handle(): int
    {
        $db=DB::connection('legacy');$schema=$db->getSchemaBuilder();
        foreach(['tm_spec_test','tm_user_test'] as $t)$this->line(($schema->hasTable($t)?'[+] ':'[-] ').$t);
        $this->info('Для связи ожидается quizzes.legacy_id = tm_test.num. Рекомендуется сначала выполнить legacy:import-quiz-bank.');
        if($this->option('dry-run'))return self::SUCCESS;
        if(!$schema->hasTable('tm_spec_test'))return self::FAILURE;

        $programs=Program::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $quizzes=Quiz::whereNotNull('legacy_id')->pluck('id','legacy_id');
        $missing=0;$done=0;
        $db->table('tm_spec_test')->orderBy('num')->chunkById(500,function($rows)use($programs,$quizzes,&$missing,&$done){
            foreach($rows as $r){$programId=$programs[(int)$r->inn]??null;$quizId=$quizzes[(int)$r->tm_test]??null;if(!$programId||!$quizId){$missing++;continue;}
                QuizAssignment::updateOrCreate(['legacy_id'=>(int)$r->num],['quiz_id'=>$quizId,'program_id'=>$programId,'title'=>$r->nazvanie?:'Базовый','attempt_limit'=>isset($r->otv_col)&&$r->otv_col!==''?(int)$r->otv_col:null,'is_active'=>(bool)$r->activ,'pass_percent'=>70]);$done++;}
        },'num');
        $this->info("Назначения: {$done}; пропущено без связанного quiz/program: {$missing}");

        if($schema->hasTable('tm_user_test')){
            $users=User::whereNotNull('legacy_id')->pluck('id','legacy_id');$assignments=QuizAssignment::whereNotNull('legacy_id')->pluck('id','legacy_id');
            $db->table('tm_user_test')->orderBy('num')->chunkById(500,function($rows)use($users,$assignments){foreach($rows as $r){
                $uid=$users[(int)$r->inn]??null;$aid=$assignments[(int)$r->test]??null;if(!$uid||!$aid)continue;
                $a=QuizAssignment::find($aid);$quiz=Quiz::find($a->quiz_id);
                $max=max(1,(int)($quiz->legacy_question_count ?: $quiz->questions()->count() ?: 100));
                $percent=min(100,round(((float)$r->res/$max)*100,2));
                QuizAttempt::updateOrCreate(['legacy_id'=>(int)$r->num],['quiz_id'=>$a->quiz_id,'quiz_assignment_id'=>$aid,'user_id'=>$uid,'score'=>(float)$r->res,'percent'=>$percent,'passed'=>$percent>=$a->pass_percent,'finished_at'=>now()]);
            }},'num');
        }
        return self::SUCCESS;
    }
}
