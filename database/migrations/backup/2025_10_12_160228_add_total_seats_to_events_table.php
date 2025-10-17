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
        Schema::table('events', function (Blueprint $table) {
            // Add total_seats field for event capacity
            $table->integer('total_seats')->default(7000)->after('max_tickets_per_order');
            
            // Add index for capacity queries
            $table->index('total_seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['total_seats']);
            $table->dropColumn('total_seats');
        });
    }
};