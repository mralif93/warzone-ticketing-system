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
        // Rename tickets table to customer_tickets
        Schema::rename('tickets', 'customer_tickets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename customer_tickets back to tickets
        Schema::rename('customer_tickets', 'tickets');
    }
};