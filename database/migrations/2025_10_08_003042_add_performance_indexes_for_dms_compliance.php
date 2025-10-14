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
        // Add performance indexes for DMS compliance
        // These indexes are critical for sub-1.0 second scan performance
        
        // Tickets table indexes
        Schema::table('tickets', function (Blueprint $table) {
            // Unique index on QR code for fast validation
            $table->unique('qrcode', 'idx_tickets_qrcode_unique');
            
            // Composite index for event and scan status
            $table->index(['event_id', 'is_scanned'], 'idx_tickets_event_scanned');
            
            // Index on status for filtering
            $table->index('status', 'idx_tickets_status');
            
            // Index on hold_until for expired holds cleanup
            $table->index('hold_until', 'idx_tickets_hold_until');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            // Index on email for fast authentication
            $table->index('email', 'idx_users_email');
            
            // Index on role for role-based queries
            $table->index('role', 'idx_users_role');
        });

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            // Index on status for filtering
            $table->index('status', 'idx_orders_status');
            
            // Index on customer_email for quick lookups
            $table->index('customer_email', 'idx_orders_customer_email');
            
            // Index on created_at for date range queries
            $table->index('created_at', 'idx_orders_created_at');
        });

        // Events table indexes
        Schema::table('events', function (Blueprint $table) {
            // Index on status for filtering
            $table->index('status', 'idx_events_status');
            
            // Index on date_time for event scheduling
            $table->index('date_time', 'idx_events_date_time');
            
            // Composite index for active events
            $table->index(['status', 'date_time'], 'idx_events_status_datetime');
        });

        // Seats table indexes - REMOVED (seats module was removed)

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            // Index on status for filtering
            $table->index('status', 'idx_payments_status');
            
            // Index on method for payment type queries
            $table->index('method', 'idx_payments_method');
            
            // Index on processed_at for date queries
            $table->index('processed_at', 'idx_payments_processed_at');
        });

        // Admittance logs table indexes
        Schema::table('admittance_logs', function (Blueprint $table) {
            // Index on scan_time for time-based queries
            $table->index('scan_time', 'idx_admittance_logs_scan_time');
            
            // Index on scan_result for filtering
            $table->index('scan_result', 'idx_admittance_logs_scan_result');
            
            // Index on gate_id for gate-specific queries
            $table->index('gate_id', 'idx_admittance_logs_gate_id');
        });

        // Audit logs table indexes
        Schema::table('audit_logs', function (Blueprint $table) {
            // Index on action for filtering
            $table->index('action', 'idx_audit_logs_action');
            
            // Index on table_name for table-specific queries
            $table->index('table_name', 'idx_audit_logs_table_name');
            
            // Index on created_at for time-based queries
            $table->index('created_at', 'idx_audit_logs_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all performance indexes
        
        // Tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique('idx_tickets_qrcode_unique');
            $table->dropIndex('idx_tickets_event_scanned');
            $table->dropIndex('idx_tickets_status');
            $table->dropIndex('idx_tickets_hold_until');
        });

        // Users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_role');
        });

        // Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_customer_email');
            $table->dropIndex('idx_orders_created_at');
        });

        // Events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_status');
            $table->dropIndex('idx_events_date_time');
            $table->dropIndex('idx_events_status_datetime');
        });

        // Seats table - REMOVED (seats module was removed)

        // Payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_status');
            $table->dropIndex('idx_payments_method');
            $table->dropIndex('idx_payments_processed_at');
        });

        // Admittance logs table
        Schema::table('admittance_logs', function (Blueprint $table) {
            $table->dropIndex('idx_admittance_logs_scan_time');
            $table->dropIndex('idx_admittance_logs_scan_result');
            $table->dropIndex('idx_admittance_logs_gate_id');
        });

        // Audit logs table
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_logs_action');
            $table->dropIndex('idx_audit_logs_table_name');
            $table->dropIndex('idx_audit_logs_created_at');
        });
    }
};