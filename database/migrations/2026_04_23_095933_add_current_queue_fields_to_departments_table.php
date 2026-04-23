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
        Schema::table('departments', function (Blueprint $table) {
            $table->integer('current_queue_number')
                ->nullable()
                ->after('name_en');

            $table->unsignedBigInteger('current_queue_id')
                ->nullable()
                ->after('current_queue_number');    
                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn([
                'current_queue_number',
                'current_queue_id'
            ]);        
        });
    }
};
