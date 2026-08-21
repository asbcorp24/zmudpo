<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('learning_sections', 'image_path')) {
            Schema::table('learning_sections', fn (Blueprint $table) => $table->string('image_path')->nullable());
        }
        if (!Schema::hasColumn('learning_sections', 'settings')) {
            Schema::table('learning_sections', fn (Blueprint $table) => $table->json('settings')->nullable());
        }
        if (!Schema::hasColumn('content_items', 'legacy_type')) {
            Schema::table('content_items', fn (Blueprint $table) => $table->unsignedTinyInteger('legacy_type')->nullable()->index());
        }
        if (!Schema::hasColumn('content_items', 'is_active')) {
            Schema::table('content_items', fn (Blueprint $table) => $table->boolean('is_active')->default(true)->index());
        }
        if (!Schema::hasColumn('content_items', 'extra_file_path')) {
            Schema::table('content_items', fn (Blueprint $table) => $table->string('extra_file_path')->nullable());
        }
        if (!Schema::hasColumn('content_items', 'allow_duplicate')) {
            Schema::table('content_items', fn (Blueprint $table) => $table->boolean('allow_duplicate')->default(false));
        }
        if (!Schema::hasColumn('content_items', 'flag')) {
            Schema::table('content_items', fn (Blueprint $table) => $table->boolean('flag')->default(false));
        }

        if (!Schema::hasTable('learning_section_instructor')) {
            Schema::create('learning_section_instructor', function (Blueprint $table) {
                $table->id();
                $table->foreignId('learning_section_id')->constrained()->cascadeOnDelete();
                $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
                $table->unique(['learning_section_id', 'instructor_id'], 'section_instructor_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_section_instructor');
        foreach (['legacy_type','is_active','extra_file_path','allow_duplicate','flag'] as $column) {
            if (Schema::hasColumn('content_items', $column)) {
                Schema::table('content_items', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
        foreach (['image_path','settings'] as $column) {
            if (Schema::hasColumn('learning_sections', $column)) {
                Schema::table('learning_sections', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }
};