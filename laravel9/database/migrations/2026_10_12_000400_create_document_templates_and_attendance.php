<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificate_templates','document_type')) $table->string('document_type',30)->default('certificate')->index();
            if (!Schema::hasColumn('certificate_templates','orientation')) $table->string('orientation',20)->default('landscape');
            if (!Schema::hasColumn('certificate_templates','organization_name')) $table->string('organization_name')->nullable();
            if (!Schema::hasColumn('certificate_templates','heading')) $table->string('heading')->nullable();
            if (!Schema::hasColumn('certificate_templates','subheading')) $table->string('subheading')->nullable();
            if (!Schema::hasColumn('certificate_templates','body_template')) $table->text('body_template')->nullable();
            if (!Schema::hasColumn('certificate_templates','footer_left')) $table->string('footer_left')->nullable();
            if (!Schema::hasColumn('certificate_templates','footer_right')) $table->string('footer_right')->nullable();
            if (!Schema::hasColumn('certificate_templates','settings')) $table->json('settings')->nullable();
            if (!Schema::hasColumn('certificate_templates','is_default')) $table->boolean('is_default')->default(false);
        });

        if (!DB::table('certificate_templates')->whereIn('document_type',['certificate','diploma','certificate_nmo'])->exists()) DB::table('certificate_templates')->insert([
            ['name'=>'Удостоверение ДПО','document_type'=>'certificate','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Удостоверение','subheading'=>'о повышении квалификации','body_template'=>'{name} успешно завершил(а) обучение по программе «{program}» в объёме {hours} часов.','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Диплом ДПО','document_type'=>'diploma','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Диплом','subheading'=>'о профессиональной переподготовке','body_template'=>'{name} прошёл(ла) профессиональную переподготовку по программе «{program}». {qualification}','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Сертификат НМО','document_type'=>'certificate_nmo','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Сертификат','subheading'=>'непрерывного медицинского образования','body_template'=>'{name} завершил(а) образовательную программу «{program}» в объёме {hours} часов.','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        if (!Schema::hasColumn('education_documents','certificate_template_id')) Schema::table('education_documents', function (Blueprint $table) {
            $table->foreignId('certificate_template_id')->nullable()->after('program_id')->constrained('certificate_templates')->nullOnDelete();
        });

        if (!Schema::hasTable('attendance_records')) Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date')->index();
            $table->string('status',20)->default('present');
            $table->unsignedSmallInteger('minutes')->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['enrollment_id','attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        Schema::table('education_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('certificate_template_id');
        });
        if (Schema::hasColumn('education_documents','certificate_template_id')) Schema::table('education_documents', function (Blueprint $table) {$table->dropConstrainedForeignId('certificate_template_id');});
    }
};
