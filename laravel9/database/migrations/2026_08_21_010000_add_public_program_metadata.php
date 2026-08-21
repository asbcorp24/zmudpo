<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::table('programs',function(Blueprint $t){$t->string('category')->nullable()->after('mode')->index();$t->decimal('price',10,2)->nullable()->after('hours');$t->text('short_description')->nullable()->after('about');$t->boolean('is_featured_public')->default(false)->after('featured')->index();});}
 public function down(): void {Schema::table('programs',fn(Blueprint $t)=>$t->dropColumn(['category','price','short_description','is_featured_public']));}
};
