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
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn([
                'is_present',
                'is_served',
                'served_at',
            ]);
            
            $table->enum('status', [
                'waiting', 
                'arrived',  
                'called',    
                'done' ,     
                'skipped'  
            ])->default('waiting')->after('queue_number');

            $table->timestamp('arrived_at')->nullable();      
            $table->timestamp('done_at')->nullable();
            $table->timestamp('skipped_at')->nullable();
            $table->boolean('is_arrived')->default(false);
            $table->boolean('is_done')->default(false);        
            $table->boolean('is_skipped')->default(false);  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
        // drop new columns added in up()
            $table->dropColumn([
                'status',
                'arrived_at',
                'done_at',
                'skipped_at',
                'is_arrived',
                'is_done',
                'is_skipped',
            ]);
    
            // restore old columns that were removed in up()
            $table->boolean('is_present')->default(false);
            $table->boolean('is_served')->default(false);
            $table->timestamp('served_at')->nullable();       
        });
    }
};
