<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{if(!Schema::hasColumn('instructor_program','legacy_id'))Schema::table('instructor_program',fn(Blueprint $t)=>$t->unsignedBigInteger('legacy_id')->nullable()->index());}public function down():void{if(Schema::hasColumn('instructor_program','legacy_id'))Schema::table('instructor_program',fn(Blueprint $t)=>$t->dropColumn('legacy_id'));}};
