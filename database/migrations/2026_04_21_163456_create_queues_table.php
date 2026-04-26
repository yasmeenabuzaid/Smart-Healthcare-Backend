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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();

            $table->integer('queue_number'); 

            $table->timestamp('expected_time')->nullable(); 

            $table->date('date');
            $table->enum('status', ['waiting', 'arrived', 'called', 'done' , 'skipped'])->default('waiting');
            
            $table->timestamp('arrived_at')->nullable();      
            $table->timestamp('called_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->timestamp('skipped_at')->nullable();

            $table->boolean('is_arrived')->default(false);
            $table->boolean('is_called')->default(false); 
            $table->boolean('is_done')->default(false);        
            $table->boolean('is_skipped')->default(false);  

            $table->unique(['department_id', 'date', 'queue_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
