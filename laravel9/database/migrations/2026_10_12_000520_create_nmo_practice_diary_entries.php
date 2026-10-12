<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  if(!Schema::hasTable('nmo_practice_diary_entries')){
   Schema::create('nmo_practice_diary_entries',function(Blueprint $t){
    $t->id();
    $t->foreignId('content_item_id')->constrained()->cascadeOnDelete();
    $t->foreignId('user_id')->constrained()->cascadeOnDelete();
    $t->date('work_date')->index();
    $t->longText('work_done');
    $t->string('supervisor')->nullable();
    $t->timestamps();
    $t->index(['content_item_id','user_id']);
   });
  }
 }
 public function down(): void {Schema::dropIfExists('nmo_practice_diary_entries');}
};