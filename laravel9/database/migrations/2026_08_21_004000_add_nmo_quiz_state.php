<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('quiz_assignments',function(Blueprint $t){$t->unsignedBigInteger('legacy_media_id')->nullable()->index()->after('legacy_id');});
  Schema::table('quiz_user_overrides',function(Blueprint $t){$t->unsignedBigInteger('legacy_id')->nullable()->index();$t->boolean('is_active_override')->nullable();});
  Schema::create('learning_section_progress',function(Blueprint $t){$t->id();$t->unsignedBigInteger('legacy_id')->nullable()->unique();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('learning_section_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('program_id')->nullable()->constrained()->nullOnDelete();$t->unsignedBigInteger('legacy_section_id')->nullable()->index();$t->boolean('completed')->default(false);$t->integer('sp')->nullable();$t->integer('psp')->nullable();$t->string('pop',20)->nullable();$t->dateTime('legacy_date')->nullable();$t->string('legacy_file')->nullable();$t->json('extra')->nullable();$t->timestamps();$t->index(['user_id','learning_section_id']);});
  Schema::create('quiz_attempt_details',function(Blueprint $t){$t->id();$t->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete()->unique();$t->string('source',30)->default('laravel');$t->string('source_path')->nullable();$t->json('questions')->nullable();$t->longText('raw_xml')->nullable();$t->json('extra')->nullable();$t->timestamps();});
  Schema::create('legacy_quiz_xml_results',function(Blueprint $t){$t->id();$t->string('filename')->unique();$t->string('checksum',64)->index();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('learning_section_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('quiz_attempt_id')->nullable()->constrained()->nullOnDelete();$t->unsignedInteger('question_count')->default(0);$t->unsignedInteger('correct_count')->default(0);$t->json('questions')->nullable();$t->longText('raw_xml')->nullable();$t->json('meta')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('legacy_quiz_xml_results');Schema::dropIfExists('quiz_attempt_details');Schema::dropIfExists('learning_section_progress');Schema::table('quiz_user_overrides',fn(Blueprint $t)=>$t->dropColumn(['legacy_id','is_active_override']));Schema::table('quiz_assignments',fn(Blueprint $t)=>$t->dropColumn('legacy_media_id'));}
};
