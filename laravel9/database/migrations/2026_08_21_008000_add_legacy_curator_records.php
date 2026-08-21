<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('legacy_curator_records',function(Blueprint $t){$t->id();$t->string('type',30)->index();$t->string('source_key',190);$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->unsignedBigInteger('legacy_user_id')->nullable()->index();$t->unsignedBigInteger('legacy_scope_id')->nullable()->index();$t->dateTime('occurred_at')->nullable()->index();$t->string('title')->nullable();$t->longText('body')->nullable();$t->longText('response')->nullable();$t->string('path',1000)->nullable();$t->string('archive_path',1000)->nullable();$t->json('meta')->nullable();$t->timestamps();$t->unique(['type','source_key']);$t->index(['type','user_id']);});}
 public function down(): void {Schema::dropIfExists('legacy_curator_records');}
};