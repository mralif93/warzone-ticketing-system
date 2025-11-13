<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix: Change user_agent from string (VARCHAR 255) to text to accommodate
     * long user agent strings from mobile browsers and embedded web views.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Change user_agent from string to text to handle long user agent strings
            // Mobile browsers, especially in-app browsers (Instagram, Facebook, etc.)
            // can send user agent strings exceeding 255 characters
            $table->text('user_agent')->nullable()->change();
            
            // Also ensure ip_address is properly sized for IPv6 (max 45 characters)
            $table->string('ip_address', 45)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Revert user_agent back to string (with max length for safety)
            $table->string('user_agent', 500)->nullable()->change();
            
            // Revert ip_address (remove explicit length)
            $table->string('ip_address')->change();
        });
    }
};
