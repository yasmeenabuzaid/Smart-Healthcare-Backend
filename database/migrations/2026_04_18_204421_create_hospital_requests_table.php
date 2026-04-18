<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::create('hospital_requests', function (Blueprint $table) {
            $table->id();

            // بيانات مقدم الطلب
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('requester_phone');

            // بيانات المستشفى المطلوبة
            $table->string('hospital_name');
            $table->string('hospital_address');
            $table->string('license_file')->nullable(); // مسار ملف الترخيص

            // حالة الطلب
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // سبب الرفض إن وُجد

            $table->timestamps();
            $table->softDeletes(); // تفعيل الحذف الآمن
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_requests');
    }
};
