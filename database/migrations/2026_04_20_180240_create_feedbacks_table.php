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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->enum('scope', ['system', 'hospital', 'department']);
            
            $table->foreignId('hospital_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            
            $table->enum('type', ['complaint', 'suggestion', 'inquiry']);
            
            $table->text('message');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
