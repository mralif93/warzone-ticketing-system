<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create new tickets table without unused fields
        Schema::create('tickets_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('zone')->index(); // Zone-based ticketing
            $table->string('qrcode')->unique(); // Unique scannable token
            $table->enum('status', ['Held', 'Sold', 'Scanned', 'Invalid', 'Refunded'])->index();
            $table->timestamp('scanned_at')->nullable();
            $table->decimal('price_paid', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            
            // Composite indexes for efficient lookups
            $table->index(['event_id', 'status']);
            $table->index(['qrcode', 'status']);
            $table->index(['event_id', 'zone', 'status']);
        });

        // Copy data from old table to new table
        DB::statement("
            INSERT INTO tickets_new (id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at)
            SELECT id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at
            FROM tickets
        ");

        // Drop old table
        Schema::dropIfExists('tickets');

        // Rename new table to original name
        Schema::rename('tickets_new', 'tickets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate old table structure
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained()->onDelete('set null');
            $table->string('qrcode')->unique();
            $table->enum('status', ['Held', 'Sold', 'Scanned', 'Invalid', 'Refunded'])->index();
            $table->boolean('is_scanned')->default(false)->index();
            $table->timestamp('scanned_at')->nullable();
            $table->string('scanned_by')->nullable();
            $table->string('gate_location')->nullable();
            $table->decimal('price_paid', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->string('zone')->index();
            
            // Composite indexes
            $table->index(['event_id', 'status']);
            $table->index(['qrcode', 'status']);
            $table->index(['event_id', 'zone', 'status']);
        });
    }
};