<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds missing fields that should have been in the original
     * order module migration but were added later via separate migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add transaction_id field if it doesn't exist
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('method');
            }
            
            // Add payment_date field if it doesn't exist
            if (!Schema::hasColumn('payments', 'payment_date')) {
                $table->datetime('payment_date')->nullable()->after('processed_at');
            }
            
            // Add notes field if it doesn't exist
            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('payment_date');
            }
            
            // Update status field to include new statuses if needed
            // Note: This is handled by the application logic, not database constraint
            
            // Add index for transaction_id if it doesn't exist
            if (!Schema::hasIndex('payments', 'payments_transaction_id_index')) {
                $table->index('transaction_id');
            }
            
            // Add index for payment_date if it doesn't exist
            if (!Schema::hasIndex('payments', 'payments_payment_date_index')) {
                $table->index('payment_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove added fields only if they exist
            if (Schema::hasColumn('payments', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('payments', 'payment_date')) {
                $table->dropColumn('payment_date');
            }
            if (Schema::hasColumn('payments', 'notes')) {
                $table->dropColumn('notes');
            }
            
            // Remove added indexes only if they exist
            if (Schema::hasIndex('payments', 'payments_transaction_id_index')) {
                $table->dropIndex(['transaction_id']);
            }
            if (Schema::hasIndex('payments', 'payments_payment_date_index')) {
                $table->dropIndex(['payment_date']);
            }
        });
    }
};