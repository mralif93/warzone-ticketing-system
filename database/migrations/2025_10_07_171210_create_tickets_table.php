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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade');
            $table->string('qrcode')->unique(); // CRITICAL: Unique scannable token
            $table->enum('status', ['Held', 'Sold', 'Scanned', 'Invalid', 'Refunded'])->index();
            $table->boolean('is_scanned')->default(false)->index();
            $table->timestamp('scanned_at')->nullable();
            $table->string('scanned_by')->nullable(); // Staff user who scanned
            $table->string('gate_location')->nullable(); // Which gate was used
            $table->decimal('price_paid', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            
            // Composite indexes for efficient lookups
            $table->index(['event_id', 'status']);
            $table->index(['qrcode', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};