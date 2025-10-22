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
        Schema::create('admittance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->dateTime('scan_time')->index();
            $table->enum('scan_result', ['SUCCESS', 'DUPLICATE', 'WRONG_GATE', 'INVALID', 'EXPIRED']);
            $table->string('gate_id', 50);
            $table->foreignId('staff_user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_info')->nullable(); // Mobile device details
            $table->string('ip_address', 45)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // No soft deletes for audit trail (immutable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admittance_logs');
    }
};