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
    Schema::create('hospitals', function (Blueprint $table) {
        $table->id();

        // 1. المعلومات الأساسية
        $table->string('name');
        $table->string('slug')->unique()->nullable(); // مفيد جداً للروابط النظيفة في المستقبل
        $table->text('description')->nullable();
        $table->string('logo')->nullable(); // مسار الشعار

        $table->string('phone');
        $table->string('emergency_phone')->nullable(); 
        $table->string('email')->unique()->nullable();
        $table->string('website')->nullable();

        $table->string('governorate')->nullable(); // المحافظة
        $table->text('address');
        $table->decimal('latitude', 10, 8)->nullable(); // إحداثيات الخريطة
        $table->decimal('longitude', 11, 8)->nullable(); // إحداثيات الخريطة

        // 4. التراخيص والتصنيفات (ضروري لاعتماد المنشآت الطبية)
        $table->string('license_number')->nullable(); // رقم الترخيص من وزارة الصحة
        $table->enum('type', ['private', 'public', 'specialized'])->default('private'); // تصنيف المستشفى

        // 5. إدارة النظام (Dashboard Logic)
        $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
        $table->text('admin_notes')->nullable(); // ملاحظات الإدارة للرجوع إليها (مثال: سبب الرفض)

        // 6. الحماية والتتبع
        $table->timestamps();
        $table->softDeletes(); // ميزة الحذف الآمن (Soft Deletes)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
