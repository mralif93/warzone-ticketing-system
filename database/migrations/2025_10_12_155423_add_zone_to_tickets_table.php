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
        Schema::table('tickets', function (Blueprint $table) {
            // Add zone field for zone-based ticketing if it doesn't exist
            if (!Schema::hasColumn('tickets', 'zone')) {
                $table->string('zone')->after('event_id')->index();
            }
            
            // Add index for zone-based queries if it doesn't exist
            if (!Schema::hasIndex('tickets', 'tickets_event_id_zone_status_index')) {
                $table->index(['event_id', 'zone', 'status']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Remove zone field
            $table->dropIndex(['event_id', 'zone', 'status']);
            $table->dropColumn('zone');
            
            // Re-add seat_id (if needed for rollback)
            $table->foreignId('seat_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};