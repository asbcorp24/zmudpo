<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('content_item_ticket_assignments')) {
            Schema::create('content_item_ticket_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('content_item_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('scope_key',80)->index();
                $table->integer('ticket_number');
                $table->timestamps();
                $table->unique(['content_item_id','user_id']);
                $table->unique(['content_item_id','scope_key','ticket_number'],'content_ticket_scope_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('content_item_ticket_assignments');
    }
};