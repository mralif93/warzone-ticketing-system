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
        // Update existing status values to lowercase
        DB::table('events')->where('status', 'Draft')->update(['status' => 'draft']);
        DB::table('events')->where('status', 'On Sale')->update(['status' => 'on_sale']);
        DB::table('events')->where('status', 'Sold Out')->update(['status' => 'sold_out']);
        DB::table('events')->where('status', 'Cancelled')->update(['status' => 'cancelled']);
        DB::table('events')->where('status', 'Inactive')->update(['status' => 'inactive']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values back to title case
        DB::table('events')->where('status', 'draft')->update(['status' => 'Draft']);
        DB::table('events')->where('status', 'on_sale')->update(['status' => 'On Sale']);
        DB::table('events')->where('status', 'sold_out')->update(['status' => 'Sold Out']);
        DB::table('events')->where('status', 'cancelled')->update(['status' => 'Cancelled']);
        DB::table('events')->where('status', 'inactive')->update(['status' => 'Inactive']);
    }
};