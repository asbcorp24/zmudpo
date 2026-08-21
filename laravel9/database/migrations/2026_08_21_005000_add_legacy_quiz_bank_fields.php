<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::table('quizzes', function(Blueprint $t){
   $t->string('legacy_path')->nullable();
   $t->string('legacy_answer_file')->nullable();
   $t->string('legacy_image')->nullable();
   $t->string('legacy_archive_path')->nullable();
   $t->string('legacy_answer_archive_path')->nullable();
   $t->unsignedInteger('legacy_question_count')->nullable();
   $t->date('legacy_date')->nullable();
   $t->string('import_status',30)->nullable();
   $t->text('import_message')->nullable();
  });
 }
 public function down(): void {
  Schema::table('quizzes',fn(Blueprint $t)=>$t->dropColumn(['legacy_path','legacy_answer_file','legacy_image','legacy_archive_path','legacy_answer_archive_path','legacy_question_count','legacy_date','import_status','import_message']));
 }
};
