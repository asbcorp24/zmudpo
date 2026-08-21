<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::table('announcements',function(Blueprint $t){$t->foreignId('group_id')->nullable()->after('program_id')->constrained('groups')->nullOnDelete();$t->dateTime('expires_at')->nullable()->after('published_at')->index();});}
 public function down(): void {Schema::table('announcements',function(Blueprint $t){$t->dropConstrainedForeignId('group_id');$t->dropColumn('expires_at');});}
};