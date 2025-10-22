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
        // Rename purchase_tickets table to purchase
        if (Schema::hasTable('purchase_tickets')) {
            Schema::rename('purchase_tickets', 'purchase');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename purchase back to purchase_tickets
        if (Schema::hasTable('purchase')) {
            Schema::rename('purchase', 'purchase_tickets');
        }
    }
};