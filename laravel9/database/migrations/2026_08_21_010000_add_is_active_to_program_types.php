<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('program_types', 'is_active')) {
            Schema::table('program_types', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('program_types', 'is_active')) {
            Schema::table('program_types', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
