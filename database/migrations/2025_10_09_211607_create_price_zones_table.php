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
        Schema::create('price_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // e.g., "Warzone Exclusive", "Level 1 Zone A"
            $table->string('code', 50)->unique(); // e.g., "WZ_EXCLUSIVE", "L1_ZONE_A"
            $table->text('description')->nullable(); // Description of the price zone
            $table->decimal('base_price', 10, 2); // Base price in RM
            $table->string('category', 50); // e.g., "Premium", "Level", "Standing"
            $table->string('color', 20)->default('#DC2626'); // Color for UI display
            $table->string('icon', 50)->nullable(); // Icon class for UI
            $table->integer('sort_order')->default(0); // For ordering in admin
            $table->boolean('is_active')->default(true); // Enable/disable price zone
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_zones');
    }
};
