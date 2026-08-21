<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('learning_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('learning_sections', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('learning_sections', 'settings')) {
                $table->json('settings')->nullable();
            }
        });

        Schema::table('content_items', function (Blueprint $table) {
            if (!Schema::hasColumn('content_items', 'legacy_type')) {
                $table->unsignedTinyInteger('legacy_type')->nullable()->index();
            }
            if (!Schema::hasColumn('content_items', 'is_active')) {
                $table->boolean('is_active')->default(true)->index();
            }
            if (!Schema::hasColumn('content_items', 'extra_file_path')) {
                $table->string('extra_file_path')->nullable();
            }
            if (!Schema::hasColumn('content_items', 'allow_duplicate')) {
                $table->boolean('allow_duplicate')->default(false);
            }
            if (!Schema::hasColumn('content_items', 'flag')) {
                $table->boolean('flag')->default(false);
            }
        });

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

        Schema::table('content_items', function (Blueprint $table) {
            foreach (['legacy_type','is_active','extra_file_path','allow_duplicate','flag'] as $column) {
                if (Schema::hasColumn('content_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('learning_sections', function (Blueprint $table) {
            foreach (['image_path','settings'] as $column) {
                if (Schema::hasColumn('learning_sections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};