<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_assignment_notifications', function(Blueprint $t){
            $t->id();
            $t->foreignId('quiz_assignment_id')->constrained('quiz_assignments')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('email')->nullable();
            $t->string('status',20)->default('sent')->index();
            $t->text('error')->nullable();
            $t->timestamp('sent_at')->nullable();
            $t->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->unique(['quiz_assignment_id','user_id'],'quiz_assignment_notification_user_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('quiz_assignment_notifications'); }
};
