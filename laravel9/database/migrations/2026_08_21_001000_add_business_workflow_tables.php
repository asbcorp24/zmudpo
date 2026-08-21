<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('enrollments','curator_id')) Schema::table('enrollments',fn(Blueprint $t)=>$t->foreignId('curator_id')->nullable()->after('group_id')->constrained('users')->nullOnDelete());
        if (!Schema::hasColumn('enrollments','ends_at')) Schema::table('enrollments',fn(Blueprint $t)=>$t->date('ends_at')->nullable()->after('started_at'));
        if (!Schema::hasColumn('enrollments','progress_percent')) Schema::table('enrollments',fn(Blueprint $t)=>$t->decimal('progress_percent',5,2)->default(0)->after('status'));
        if (!Schema::hasColumn('enrollments','blocked_at')) Schema::table('enrollments',fn(Blueprint $t)=>$t->timestamp('blocked_at')->nullable());
        if (!Schema::hasColumn('enrollments','admin_comment')) Schema::table('enrollments',fn(Blueprint $t)=>$t->text('admin_comment')->nullable());

        if (!Schema::hasColumn('learning_sections','is_required')) Schema::table('learning_sections',fn(Blueprint $t)=>$t->boolean('is_required')->default(true));
        if (!Schema::hasColumn('learning_sections','available_from')) Schema::table('learning_sections',fn(Blueprint $t)=>$t->date('available_from')->nullable());
        if (!Schema::hasColumn('learning_sections','available_until')) Schema::table('learning_sections',fn(Blueprint $t)=>$t->date('available_until')->nullable());
        if (!Schema::hasColumn('learning_sections','prerequisite_section_id')) Schema::table('learning_sections',fn(Blueprint $t)=>$t->foreignId('prerequisite_section_id')->nullable()->constrained('learning_sections')->nullOnDelete());

        Schema::create('final_works', function(Blueprint $t){
            $t->id(); $t->unsignedBigInteger('legacy_id')->nullable()->index();
            $t->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('title')->nullable(); $t->string('file_path')->nullable();
            $t->unsignedTinyInteger('antiplagiarism_percent')->nullable();
            $t->string('status',24)->default('draft')->index();
            $t->decimal('score',8,2)->nullable(); $t->text('comment')->nullable(); $t->text('review_comment')->nullable();
            $t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('submitted_at')->nullable(); $t->timestamp('reviewed_at')->nullable(); $t->timestamps();
        });
        Schema::create('curator_messages', function(Blueprint $t){
            $t->id(); $t->unsignedBigInteger('legacy_id')->nullable()->index();
            $t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('curator_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('enrollment_id')->nullable()->constrained()->cascadeOnDelete();
            $t->text('message'); $t->boolean('from_curator')->default(false); $t->timestamp('read_at')->nullable(); $t->timestamps();
        });
        Schema::create('login_events', function(Blueprint $t){
            $t->id(); $t->unsignedBigInteger('legacy_id')->nullable()->index(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamp('logged_in_at')->index(); $t->string('ip',45)->nullable(); $t->string('user_agent',500)->nullable(); $t->string('note',500)->nullable(); $t->timestamps();
        });
        Schema::create('archive_records', function(Blueprint $t){
            $t->id(); $t->unsignedBigInteger('legacy_id')->nullable()->index(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('program_id')->nullable()->constrained()->nullOnDelete(); $t->string('type',40)->index(); $t->string('title')->nullable();
            $t->date('started_at')->nullable(); $t->date('ended_at')->nullable(); $t->decimal('score',8,2)->nullable(); $t->json('data')->nullable(); $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('archive_records'); Schema::dropIfExists('login_events'); Schema::dropIfExists('curator_messages'); Schema::dropIfExists('final_works');
    }
};