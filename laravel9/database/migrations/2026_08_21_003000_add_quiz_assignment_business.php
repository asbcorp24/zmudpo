<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_assignments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('legacy_id')->nullable()->unique(); // tm_spec_test.num
            $t->foreignId('quiz_id')->constrained()->cascadeOnDelete(); // quiz legacy_id = tm_test.num after bank migration
            $t->foreignId('program_id')->constrained()->cascadeOnDelete();
            $t->foreignId('learning_section_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedTinyInteger('pass_percent')->default(70);
            $t->unsignedSmallInteger('attempt_limit')->nullable();
            $t->dateTime('available_from')->nullable();
            $t->dateTime('available_until')->nullable();
            $t->boolean('is_active')->default(true);
            $t->boolean('is_required')->default(false);
            $t->timestamps();
            $t->index(['program_id','is_active']);
        });
        Schema::table('quiz_attempts', function (Blueprint $t) {
            $t->foreignId('quiz_assignment_id')->nullable()->after('quiz_id')->constrained('quiz_assignments')->nullOnDelete();
            $t->text('admin_comment')->nullable();
            $t->foreignId('adjusted_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('adjusted_at')->nullable();
            $t->decimal('original_score',10,2)->nullable();
            $t->decimal('original_percent',5,2)->nullable();
        });
        Schema::table('quiz_user_overrides', function (Blueprint $t) {
            $t->foreignId('quiz_assignment_id')->nullable()->after('quiz_id')->constrained('quiz_assignments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_user_overrides',fn(Blueprint $t)=>$t->dropConstrainedForeignId('quiz_assignment_id'));
        Schema::table('quiz_attempts', function (Blueprint $t) {
            $t->dropConstrainedForeignId('quiz_assignment_id');
            $t->dropConstrainedForeignId('adjusted_by');
            $t->dropColumn(['admin_comment','adjusted_at','original_score','original_percent']);
        });
        Schema::dropIfExists('quiz_assignments');
    }
};