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
        Schema::create('department_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();

            $table->enum('day_of_week', [
                'sat','sun','mon','tue','wed','thu','fri'
            ]);

            $table->time('start_time');
            $table->time('end_time');

            $table->string('service_type_ar');
            $table->string('service_type_en');

            $table->integer('avg_visit_duration'); 
            $table->integer('max_patients');

            $table->boolean('is_active')->default(true); // to close a full shift
            $table->boolean('is_closed')->default(false);  // for the holiday           

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_schedules');
    }
};
