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
        // First, drop the foreign key constraint
        DB::statement('PRAGMA foreign_keys=OFF;');
        
        // Create a new table without the foreign key constraint
        DB::statement('
            CREATE TABLE tickets_temp AS 
            SELECT id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at
            FROM tickets
        ');
        
        // Drop the original table
        DB::statement('DROP TABLE tickets;');
        
        // Create new table with nullable order_id
        DB::statement('
            CREATE TABLE tickets (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NULL,
                event_id INTEGER NOT NULL,
                zone VARCHAR NOT NULL,
                qrcode VARCHAR NOT NULL UNIQUE,
                status VARCHAR CHECK (status IN ("Held", "Sold", "Scanned", "Invalid", "Refunded")) NOT NULL,
                scanned_at DATETIME NULL,
                price_paid DECIMAL(10,2) NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                deleted_at DATETIME NULL,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            )
        ');
        
        // Copy data back
        DB::statement('
            INSERT INTO tickets (id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at)
            SELECT id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at
            FROM tickets_temp
        ');
        
        // Drop temp table
        DB::statement('DROP TABLE tickets_temp;');
        
        // Re-enable foreign keys
        DB::statement('PRAGMA foreign_keys=ON;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate with foreign key constraint
        DB::statement('PRAGMA foreign_keys=OFF;');
        
        DB::statement('
            CREATE TABLE tickets_temp AS 
            SELECT id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at
            FROM tickets
        ');
        
        DB::statement('DROP TABLE tickets;');
        
        DB::statement('
            CREATE TABLE tickets (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                event_id INTEGER NOT NULL,
                zone VARCHAR NOT NULL,
                qrcode VARCHAR NOT NULL UNIQUE,
                status VARCHAR CHECK (status IN ("Held", "Sold", "Scanned", "Invalid", "Refunded")) NOT NULL,
                scanned_at DATETIME NULL,
                price_paid DECIMAL(10,2) NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                deleted_at DATETIME NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            )
        ');
        
        DB::statement('
            INSERT INTO tickets (id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at)
            SELECT id, order_id, event_id, zone, qrcode, status, scanned_at, price_paid, created_at, updated_at, deleted_at
            FROM tickets_temp
        ');
        
        DB::statement('DROP TABLE tickets_temp;');
        DB::statement('PRAGMA foreign_keys=ON;');
    }
};