<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * TICKET MODULE - Handles ticket types/availability and individual purchased tickets
     */
    public function up(): void
    {
        // Create tickets table (ticket types/availability)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ticket type name (VIP, General, Student)
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->integer('sold_seats')->default(0);
            $table->integer('scanned_seats')->default(0);
            $table->string('status')->default('active'); // Active, Sold Out, Inactive
            $table->text('description')->nullable();
            $table->string('seating_image')->nullable(); // Seating location image path
            $table->boolean('is_combo')->default(false); // Is this a combo ticket type
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['event_id', 'status']);
            $table->index('name');
            $table->index('price');
        });

        // Create purchase table (individual customer tickets)
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_type_id')->constrained('tickets')->onDelete('cascade');
            $table->string('zone'); // Keep for compatibility
            
            // Multi-day event support (max 2 days)
            $table->date('event_day')->nullable(); // Specific date for multi-day events
            $table->string('event_day_name')->nullable(); // e.g., "Day 1", "Friday", "Opening Day"
            
            // Combo purchase tracking
            $table->boolean('is_combo_purchase')->default(false); // Is this part of a combo purchase
            $table->string('combo_group_id')->nullable(); // Groups combo tickets together
            $table->decimal('original_price', 10, 2)->nullable(); // Original price before discount
            $table->decimal('discount_amount', 10, 2)->default(0); // Discount amount applied
            
            $table->string('qrcode')->unique();
            $table->string('status'); // sold, held, scanned, invalid, refunded
            $table->datetime('scanned_at')->nullable();
            $table->decimal('price_paid', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['order_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index(['event_id', 'event_day']); // For multi-day event queries
            $table->index(['ticket_type_id', 'status']);
            $table->index('qrcode');
            $table->index('zone');
            $table->index('event_day'); // For date-based queries
            $table->index('combo_group_id'); // For combo purchase queries
            $table->index('is_combo_purchase'); // For filtering combo purchases
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('tickets');
    }
};