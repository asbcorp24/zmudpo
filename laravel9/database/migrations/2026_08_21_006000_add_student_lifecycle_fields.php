<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('final_works', function(Blueprint $t){
            if (!Schema::hasColumn('final_works','final_work_definition_id')) $t->foreignId('final_work_definition_id')->nullable()->after('user_id')->constrained('final_work_definitions')->nullOnDelete();
            if (!Schema::hasColumn('final_works','final_work_theme_id')) $t->foreignId('final_work_theme_id')->nullable()->after('final_work_definition_id')->constrained('final_work_themes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('final_works', function(Blueprint $t){
            if (Schema::hasColumn('final_works','final_work_theme_id')) { $t->dropForeign(['final_work_theme_id']); $t->dropColumn('final_work_theme_id'); }
            if (Schema::hasColumn('final_works','final_work_definition_id')) { $t->dropForeign(['final_work_definition_id']); $t->dropColumn('final_work_definition_id'); }
        });
    }
};
