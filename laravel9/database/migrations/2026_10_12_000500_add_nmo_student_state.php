<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('personal_data_consent_at')->nullable()->after('registration_confirmed_at');
        });

        Schema::create('announcement_user_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('acknowledged_at');
            $table->timestamps();
            $table->unique(['announcement_id','user_id']);
        });

        Schema::create('learning_section_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('visited_at')->index();
            $table->timestamps();
            $table->index(['learning_section_id','user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_section_visits');
        Schema::dropIfExists('announcement_user_acknowledgements');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('personal_data_consent_at');
        });
    }
};