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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // البيانات الأساسية
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // الدور الوظيفي (يمكنك إضافة أدوار أخرى مستقبلاً)
            $table->enum('role', ['admin', 'doctor', 'receptionist', 'manager'])->default('manager');

            // الربط مع جدول المستشفيات (Foreign Key)
            $table->foreignId('hospital_id')
                  ->constrained('hospitals')
                  ->onDelete('cascade'); // حذف الموظف تلقائياً إذا تم حذف المستشفى بالكامل

            $table->timestamps();
            $table->softDeletes(); // تفعيل الحذف الآمن
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
