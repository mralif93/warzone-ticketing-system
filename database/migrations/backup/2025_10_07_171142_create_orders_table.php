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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_email')->index();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['Pending', 'Paid', 'Failed', 'Refunded', 'Cancelled'])->index();
            $table->string('payment_method')->nullable(); // Credit Card, Cash, Comp
            $table->text('notes')->nullable();
            $table->timestamp('held_until')->nullable(); // For seat hold timeout
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};