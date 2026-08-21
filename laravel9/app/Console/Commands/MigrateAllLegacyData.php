<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateAllLegacyData extends Command
{
    protected $signature = 'legacy:migrate-all
        {--dry-run : Проверить подключения, таблицы и этапы без записи}
        {--program-images-dir= : Путь к legacy timg}
        {--tests-dir= : Путь к legacy testy}
        {--answers-dir= : Путь к legacy otv}
        {--xml-dir= : Путь к legacy userxml}
        {--workbooks-dir= : Путь к legacy tetrad/user}
        {--user-files-dir= : Путь к legacy usrimg}
        {--force-reparse : Пересоздать нормализованные вопросы из legacy-пакетов тестов}
        {--continue-on-error : Не останавливать следующие этапы при ошибке одного импортёра}';

    protected $description = 'Полный последовательный перенос данных из старой базы ZMUDPO в Laravel';

    public function handle(): int
    {
        $dry = (bool)$this->option('dry-run');
        $continue = (bool)$this->option('continue-on-error');

        $this->newLine();
        $this->info('ZMUDPO: полный импорт legacy → Laravel');
        $this->line($dry ? 'Режим: DRY RUN — запись не выполняется.' : 'Режим: ИМПОРТ. Повторный запуск допустим: импортёры используют legacy_id/updateOrCreate.');
        $this->newLine();

        if (!$this->checkConnections()) return self::FAILURE;

        $legacy = DB::connection('legacy');
        $legacySchema = $legacy->getSchemaBuilder();

        $requiredCore = ['tm_spec','tm_grupp','tm_user'];
        $missingCore = array_values(array_filter($requiredCore, fn($t) => !$legacySchema->hasTable($t)));
        if ($missingCore) {
            $this->error('Нет обязательных таблиц старой БД: '.implode(', ', $missingCore));
            return self::FAILURE;
        }

        $this->showLegacyOverview($legacy, $legacySchema);

        $stages = [];
        if ($dry) {
            $stages[] = $this->dryCore($legacy);
        } else {
            $args = ['--core' => true];
            if ($this->option('program-images-dir')) $args['--program-images-dir'] = $this->option('program-images-dir');
            $stages[] = $this->runStage('Основные данные', 'legacy:import', $args, $continue);
            if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);
        }

        $stages[] = $this->runStage('Справочники, документы, преподаватели, новости', 'legacy:import-admin', $dry ? ['--dry-run'=>true] : [], $continue);
        if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);

        $stages[] = $this->runStage('Бизнес-история слушателей', 'legacy:import-business', $dry ? ['--dry-run'=>true] : [], $continue);
        if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);

        if ($legacySchema->hasTable('tm_test')) {
            $args = $dry ? ['--dry-run'=>true] : [];
            foreach (['tests-dir','answers-dir'] as $opt) if ($this->option($opt)) $args['--'.$opt] = $this->option($opt);
            if (!$dry && $this->option('force-reparse')) $args['--force-reparse'] = true;
            $stages[] = $this->runStage('Банк тестов', 'legacy:import-quiz-bank', $args, $continue);
            if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);
        } else {
            $stages[] = $this->skippedStage('Банк тестов', 'tm_test отсутствует');
        }

        if ($legacySchema->hasTable('tm_spec_test')) {
            $stages[] = $this->runStage('Назначения и результаты тестов', 'legacy:import-quiz-business', $dry ? ['--dry-run'=>true] : [], $continue);
            if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);
        } else {
            $stages[] = $this->skippedStage('Назначения и результаты тестов', 'tm_spec_test отсутствует');
        }

        $hasNmo = $legacySchema->hasTable('tm_nmo_razd_user') || $legacySchema->hasTable('tm_nmo_razd_media_user_act_test');
        if ($hasNmo) {
            $args = $dry ? ['--dry-run'=>true] : [];
            if ($this->option('xml-dir')) $args['--xml-dir'] = $this->option('xml-dir');
            $stages[] = $this->runStage('NMO: прогресс, активации, XML', 'legacy:import-nmo-quiz-state', $args, $continue);
            if (!$stages[array_key_last($stages)]['ok'] && !$continue) return $this->finish($stages, false);
        } else {
            $stages[] = $this->skippedStage('NMO: прогресс, активации, XML', 'NMO-таблицы отсутствуют');
        }

        $hasCurator = collect(['tm_login_dat','nmo_otm_pos','tm_nmo_pract','tm_user_sh','tm_nmo_user_file','tm_chat_kurator','tm_obiav'])
            ->contains(fn($t) => $legacySchema->hasTable($t));
        if ($hasCurator) {
            $args = $dry ? ['--dry-run'=>true] : [];
            foreach (['workbooks-dir','user-files-dir'] as $opt) if ($this->option($opt)) $args['--'.$opt] = $this->option($opt);
            $stages[] = $this->runStage('Архив кабинета куратора', 'legacy:import-curator', $args, $continue);
        } else {
            $stages[] = $this->skippedStage('Архив кабинета куратора', 'таблицы кабинета куратора отсутствуют');
        }

        return $this->finish($stages, collect($stages)->where('ok', false)->isEmpty());
    }

    private function checkConnections(): bool
    {
        try {
            DB::connection()->getPdo();
            $this->info('[+] Новая БД: '.DB::connection()->getDatabaseName());
        } catch (\Throwable $e) {
            $this->error('Не удалось подключиться к новой БД: '.$e->getMessage());
            return false;
        }
        try {
            DB::connection('legacy')->getPdo();
            $this->info('[+] Legacy БД: '.DB::connection('legacy')->getDatabaseName());
        } catch (\Throwable $e) {
            $this->error('Не удалось подключиться к legacy БД. Проверь LEGACY_DB_* в .env.');
            $this->line($e->getMessage());
            return false;
        }
        return true;
    }

    private function showLegacyOverview($db, $schema): void
    {
        $tables = [
            'tm_spec'=>'Программы', 'tm_grupp'=>'Группы', 'tm_user'=>'Слушатели',
            'tm_test'=>'Тесты', 'tm_spec_test'=>'Назначения тестов', 'tm_user_test'=>'Результаты тестов',
            'tm_nmo_razd_user'=>'NMO прогресс', 'tm_news'=>'Новости', 'tm_otziv'=>'Отзывы',
            'tm_nmo_pract'=>'Практика куратора', 'tm_nmo_user_file'=>'Файлы студентов', 'tm_chat_kurator'=>'Чат куратора'
        ];
        $rows=[];
        foreach ($tables as $table=>$label) {
            if (!$schema->hasTable($table)) { $rows[]=[$label,$table,'—','нет']; continue; }
            try { $count=$db->table($table)->count(); } catch (\Throwable $e) { $count='ошибка'; }
            $rows[]=[$label,$table,$count,'есть'];
        }
        $this->newLine();
        $this->table(['Раздел','Legacy table','Записей','Статус'],$rows);
    }

    private function dryCore($db): array
    {
        $this->newLine();
        $this->info('== Основные данные [DRY RUN] ==');
        $counts=[];
        foreach (['tm_spec'=>'программ','tm_grupp'=>'групп','tm_user'=>'слушателей'] as $t=>$label) {
            $n=$db->table($t)->count(); $this->line("{$t}: {$n} {$label}"); $counts[]=$n;
        }
        return ['name'=>'Основные данные','ok'=>true,'status'=>'проверено'];
    }

    private function runStage(string $name, string $command, array $arguments, bool $continue): array
    {
        $this->newLine();
        $this->info("== {$name} ==");
        try {
            $code=$this->call($command,$arguments);
            if ($code===self::SUCCESS) return ['name'=>$name,'ok'=>true,'status'=>'готово'];
            $this->error("Этап завершился с кодом {$code}: {$command}");
            return ['name'=>$name,'ok'=>false,'status'=>'ошибка '.$code];
        } catch (\Throwable $e) {
            $this->error("Ошибка этапа «{$name}»: {$e->getMessage()}");
            if (!$continue) $this->line('Используй --continue-on-error, если нужно продолжать остальные независимые этапы.');
            return ['name'=>$name,'ok'=>false,'status'=>'exception'];
        }
    }

    private function skippedStage(string $name, string $reason): array
    {
        $this->warn("Пропуск: {$name} — {$reason}");
        return ['name'=>$name,'ok'=>true,'status'=>'пропущено'];
    }

    private function finish(array $stages, bool $ok): int
    {
        $this->newLine();
        $this->info('Итог по этапам');
        $this->table(['Этап','Результат'], array_map(fn($s)=>[$s['name'],$s['status']],$stages));

        if (!$this->option('dry-run')) $this->showModernSummary();

        if ($ok) {
            $this->newLine();
            $this->info($this->option('dry-run') ? 'Проверка завершена. Можно запускать без --dry-run.' : 'Полный импорт завершён.');
            return self::SUCCESS;
        }
        $this->error('Импорт завершён с ошибками. Уже импортированные этапы не удаляются; после исправления команду можно запустить повторно.');
        return self::FAILURE;
    }

    private function showModernSummary(): void
    {
        $map=[
            'programs'=>'Программы','groups'=>'Группы','users'=>'Пользователи','enrollments'=>'Записи на обучение',
            'quizzes'=>'Тесты','quiz_assignments'=>'Назначения тестов','quiz_attempts'=>'Попытки тестов',
            'learning_section_progress'=>'NMO-прогресс','news'=>'Новости','testimonials'=>'Отзывы',
            'legacy_curator_records'=>'Архив куратора'
        ];
        $rows=[];
        foreach($map as $table=>$label){
            if(!Schema::hasTable($table))continue;
            try{$rows[]=[$label,DB::table($table)->count()];}catch(\Throwable $e){}
        }
        if($rows){$this->newLine();$this->info('Состояние новой базы после импорта');$this->table(['Раздел','Записей'],$rows);}
    }
}
