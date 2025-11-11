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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('image_path'); // Path to the image file
            $table->string('title')->nullable(); // Optional title for the image
            $table->text('description')->nullable(); // Optional description
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true); // Whether to display the image
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['event_id', 'is_active']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
