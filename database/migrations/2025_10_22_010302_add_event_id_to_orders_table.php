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
        Schema::table('orders', function (Blueprint $table) {
            // First add the column as nullable
            $table->unsignedBigInteger('event_id')->after('user_id')->nullable();
        });
        
        // Update existing orders to have a default event_id (if any exist)
        // For testing purposes, we'll set all existing orders to event_id = 1
        if (\DB::table('orders')->count() > 0) {
            \DB::table('orders')->update(['event_id' => 1]);
        }
        
        // Now make it NOT NULL and add foreign key constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable(false)->change();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->index(['event_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'status']);
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};