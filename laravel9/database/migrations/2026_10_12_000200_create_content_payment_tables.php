<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_item_id')->constrained()->cascadeOnDelete();
            $table->string('provider',30)->default('robokassa');
            $table->decimal('amount',10,2);
            $table->string('status',20)->default('pending')->index();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('content_item_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source',30)->default('payment');
            $table->timestamp('granted_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id','content_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_item_entitlements');
        Schema::dropIfExists('payment_transactions');
    }
};
