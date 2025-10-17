<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * EVENT MODULE - Handles event information and management
     */
    public function up(): void
    {
        // Create events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->datetime('date_time');
            $table->string('status')->default('Draft'); // Draft, On Sale, Sold Out, Cancelled
            $table->integer('max_tickets_per_order')->default(8);
            $table->text('description')->nullable();
            $table->string('venue')->nullable();
            $table->integer('total_seats')->default(7000);
            $table->timestamps();
            $table->softDeletes();
            
            // Multi-day event support (max 2 days)
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            
            // Combo discount settings
            $table->decimal('combo_discount_percentage', 5, 2)->default(10.00); // 10% discount for 2-day combo
            $table->boolean('combo_discount_enabled')->default(true); // Enable/disable combo discount
            
            // Default event indicator
            $table->boolean('default')->default(false); // Mark as default event
            
            // Indexes
            $table->index(['status', 'date_time']);
            $table->index('venue');
            $table->index('date_time');
        });

        // Ensure only one event can be default at a time
        // This constraint is enforced by application logic in the Event model
        // The constraint ensures data integrity for the default event functionality
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};