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
        // Step 1: Drop the old tickets table (it was for individual tickets)
        if (Schema::hasTable('tickets')) {
            Schema::drop('tickets');
        }

        // Step 2: Rename zones table to tickets (zones will become ticket types/availability)
        if (Schema::hasTable('zones')) {
            Schema::rename('zones', 'tickets');
        }

        // Step 3: Rename customer_tickets to purchase_tickets (individual purchased tickets)
        if (Schema::hasTable('customer_tickets')) {
            Schema::rename('customer_tickets', 'purchase_tickets');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename tables back
        if (Schema::hasTable('purchase_tickets')) {
            Schema::rename('purchase_tickets', 'customer_tickets');
        }
        
        if (Schema::hasTable('tickets')) {
            Schema::rename('tickets', 'zones');
        }
    }
};