<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ORDER MODULE - Handles customer orders and payments
     */
    public function up(): void
    {
        // Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('customer_email');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('status'); // pending, paid, cancelled, refunded
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('held_until')->nullable();
            $table->string('qrcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index('order_number');
            $table->index('customer_email');
        });

        // Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('method');
            $table->string('transaction_id')->nullable(); // Added: External transaction reference
            $table->decimal('amount', 10, 2);
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('refund_date')->nullable();
            $table->text('refund_reason')->nullable();
            $table->string('refund_method')->nullable();
            $table->string('refund_reference')->nullable();
            $table->string('currency')->default('USD');
            $table->string('status'); // pending, succeeded, failed, cancelled, refunded, partially refunded
            $table->text('stripe_response')->nullable();
            $table->text('failure_reason')->nullable();
            $table->datetime('processed_at')->nullable();
            $table->datetime('payment_date')->nullable(); // Added: Actual payment completion date
            $table->text('notes')->nullable(); // Added: Payment notes and comments
            $table->timestamps();
            
            // Indexes
            $table->index(['order_id', 'status']);
            $table->index('stripe_charge_id');
            $table->index('refund_date');
            $table->index('transaction_id'); // Added: Index for transaction lookup
            $table->index('payment_date'); // Added: Index for payment date queries
        });

        // Add foreign key constraint for purchase table's order_id
        // This is done here because the orders table must exist first
        Schema::table('purchase', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint first
        Schema::table('purchase', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });
        
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
    }
};