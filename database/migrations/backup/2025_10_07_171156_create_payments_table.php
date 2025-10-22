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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('stripe_charge_id')->unique()->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->enum('method', ['Credit Card', 'Cash', 'Comp', 'Refund']);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['Pending', 'Succeeded', 'Failed', 'Cancelled', 'Refunded']);
            $table->json('stripe_response')->nullable(); // Store Stripe API response
            $table->text('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // No soft deletes for financial records (immutable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};