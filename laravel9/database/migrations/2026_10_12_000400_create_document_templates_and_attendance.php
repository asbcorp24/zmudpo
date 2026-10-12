<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document_type',30)->default('certificate')->index();
            $table->string('orientation',20)->default('landscape');
            $table->string('organization_name')->nullable();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->text('body_template')->nullable();
            $table->string('footer_left')->nullable();
            $table->string('footer_right')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('certificate_templates')->insert([
            ['name'=>'Удостоверение ДПО','document_type'=>'certificate','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Удостоверение','subheading'=>'о повышении квалификации','body_template'=>'{name} успешно завершил(а) обучение по программе «{program}» в объёме {hours} часов.','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Диплом ДПО','document_type'=>'diploma','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Диплом','subheading'=>'о профессиональной переподготовке','body_template'=>'{name} прошёл(ла) профессиональную переподготовку по программе «{program}». {qualification}','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Сертификат НМО','document_type'=>'certificate_nmo','orientation'=>'landscape','organization_name'=>'ГАПОУ «Зеленодольский медицинский колледж»','heading'=>'Сертификат','subheading'=>'непрерывного медицинского образования','body_template'=>'{name} завершил(а) образовательную программу «{program}» в объёме {hours} часов.','footer_left'=>'Руководитель __________________','footer_right'=>'М.П.','is_default'=>1,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        Schema::table('education_documents', function (Blueprint $table) {
            $table->foreignId('certificate_template_id')->nullable()->after('program_id')->constrained('certificate_templates')->nullOnDelete();
        });

        Schema::create('attendance_records', function (Blueprint $table) {
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
        Schema::dropIfExists('certificate_templates');
    }
};
