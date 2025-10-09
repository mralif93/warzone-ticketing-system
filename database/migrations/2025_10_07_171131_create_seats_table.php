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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('section', 50)->index();
            $table->string('row', 50)->index();
            $table->integer('number')->index();
            $table->string('price_zone', 50)->index();
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->boolean('is_accessible')->default(false);
            $table->string('seat_type')->default('Standard'); // Standard, VIP, Premium
            $table->timestamps();
            $table->softDeletes();
            
            // Composite index for efficient seat lookups
            $table->index(['section', 'row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};