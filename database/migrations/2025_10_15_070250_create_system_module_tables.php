<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * SYSTEM MODULE - Handles system-wide functionality (logging, scanning, etc.)
     */
    public function up(): void
    {
        // Create admittance_logs table (gate scanning)
        Schema::create('admittance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_ticket_id')->constrained('purchase')->onDelete('cascade');
            $table->datetime('scan_time');
            $table->string('scan_result'); // Valid, Invalid, Already Used
            $table->string('gate_id');
            $table->foreignId('staff_user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_info')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['purchase_ticket_id', 'scan_time']);
            $table->index(['staff_user_id', 'scan_time']);
            $table->index('scan_result');
        });

        // Create audit_logs table (system audit trail)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // CREATE, UPDATE, DELETE
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['table_name', 'record_id']);
            $table->index('action');
        });

        // Create failed_jobs table (Laravel queue system)
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Create settings table (system configuration)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('admittance_logs');
    }
};