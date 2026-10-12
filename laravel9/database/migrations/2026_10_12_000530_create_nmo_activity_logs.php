<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  if(!Schema::hasTable('nmo_activity_logs')){
   Schema::create('nmo_activity_logs',function(Blueprint $t){
    $t->id();
    $t->foreignId('user_id')->constrained()->cascadeOnDelete();
    $t->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
    $t->foreignId('learning_section_id')->nullable()->constrained()->nullOnDelete();
    $t->foreignId('content_item_id')->nullable()->constrained()->nullOnDelete();
    $t->string('action',80)->index();
    $t->string('label')->nullable();
    $t->json('meta')->nullable();
    $t->string('ip',45)->nullable();
    $t->timestamps();
    $t->index(['user_id','created_at']);
   });
  }
 }
 public function down(): void {Schema::dropIfExists('nmo_activity_logs');}
};