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

            // drop old columns
            $table->dropColumn([
                'is_present',
                'is_called',
                'is_served',
                'called_at',
                'served_at',
            ]);

            // add new columns
            $table->boolean('is_arrived')->default(false);
            $table->timestamp('arrived_at')->nullable();

            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {

            // rollback (old structure)
            $table->boolean('is_present')->default(false);
            $table->boolean('is_called')->default(false);
            $table->boolean('is_served')->default(false);

            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();

            // remove new ones
            $table->dropColumn([
                'is_arrived',
                'arrived_at',
                'is_checked_in',
                'checked_in_at',
            ]);
        });
    }
};
