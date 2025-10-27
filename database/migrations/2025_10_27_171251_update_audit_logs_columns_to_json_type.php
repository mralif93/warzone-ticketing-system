<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN, so we'll use raw SQL
        $driver = config('database.default');
        
        if ($driver === 'sqlite') {
            // SQLite: Create new table with JSON columns
            DB::statement('
                CREATE TABLE audit_logs_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    action TEXT NOT NULL,
                    table_name TEXT NOT NULL,
                    record_id INTEGER NOT NULL,
                    old_values TEXT NULL,
                    new_values TEXT NULL,
                    ip_address TEXT NOT NULL,
                    user_agent TEXT NULL,
                    description TEXT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');
            
            // Copy data
            DB::statement('
                INSERT INTO audit_logs_new SELECT * FROM audit_logs
            ');
            
            // Drop old table
            DB::statement('DROP TABLE audit_logs');
            
            // Rename new table
            DB::statement('ALTER TABLE audit_logs_new RENAME TO audit_logs');
            
            // Recreate indexes
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index(['user_id', 'created_at']);
                $table->index(['table_name', 'record_id']);
                $table->index('action');
            });
        } else {
            // MySQL/PostgreSQL: Just change column type
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->json('old_values')->nullable()->change();
                $table->json('new_values')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = config('database.default');
        
        if ($driver === 'sqlite') {
            // SQLite: Convert back to TEXT
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->text('old_values')->nullable()->change();
                $table->text('new_values')->nullable()->change();
            });
        } else {
            // MySQL/PostgreSQL: Convert back
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->text('old_values')->nullable()->change();
                $table->text('new_values')->nullable()->change();
            });
        }
    }
};
