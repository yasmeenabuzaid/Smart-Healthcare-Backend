<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_requests', function (Blueprint $table) {
            $table->id();

            // بيانات المريض
            $table->string('patient_name');
            $table->string('patient_phone');

            // بيانات التأمين
            $table->string('insurance_company'); // اسم شركة التأمين
            $table->string('insurance_number')->nullable(); // رقم البوليصة إن وجد
            $table->string('image_path'); // مسار الصورة المرفوعة من الـ API

            // حالة الطلب وملاحظات الإدارة
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // سبب الرفض أو ملاحظات القبول

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_requests');
    }
};
