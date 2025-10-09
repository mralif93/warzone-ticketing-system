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
        Schema::table('users', function (Blueprint $table) {
            // Address fields
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postcode', 20)->nullable();
            $table->string('country', 100)->nullable();
            
            // Update role to match DMS specification (using string for SQLite compatibility)
            $table->string('role')->default('Customer')->change();
            
            // Phone number field
            $table->string('phone_number', 20)->nullable();
            
            // Add indexes for performance
            $table->index('role');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address_line_1',
                'address_line_2', 
                'address_line_3',
                'city',
                'state',
                'postcode',
                'country',
                'phone_number'
            ]);
            
            $table->dropIndex(['role']);
            $table->dropIndex(['phone_number']);
        });
    }
};