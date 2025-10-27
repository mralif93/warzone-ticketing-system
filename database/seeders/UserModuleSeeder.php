<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserModuleSeeder extends Seeder
{
    /**
     * Run the database seeds for User Module.
     */
    public function run(): void
    {
        $this->command->info('Seeding User Module...');

        // Clear existing users to prevent duplicates
        $this->command->info('Clearing existing users...');
        User::query()->delete();

        // Create Administrator
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'administrator',
            'is_active' => true,
            'phone' => '+60123456789',
            'department' => 'it_department',
            'address_line_1' => '123 Admin Tower',
            'address_line_2' => 'Level 10',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50000',
            'country' => 'Malaysia',
            'phone_number' => '+60123456789',
        ]);

        // Create Gate Staff
        User::create([
            'name' => 'Gate Staff Manager',
            'email' => 'gate@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'gate_staff',
            'is_active' => true,
            'phone' => '+60123456790',
            'department' => 'Operations',
            'address_line_1' => '456 Gate House',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50001',
            'country' => 'Malaysia',
            'phone_number' => '+60123456790',
        ]);

        // Create Counter Staff
        User::create([
            'name' => 'Counter Staff Lead',
            'email' => 'counter@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'counter_staff',
            'is_active' => true,
            'phone' => '+60123456791',
            'department' => 'customer_service',
            'address_line_1' => '789 Counter Plaza',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50002',
            'country' => 'Malaysia',
            'phone_number' => '+60123456791',
        ]);

        // Create Support Staff
        User::create([
            'name' => 'Support Staff Lead',
            'email' => 'support@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'support_staff',
            'is_active' => true,
            'phone' => '+60123456792',
            'department' => 'customer_support',
            'address_line_1' => '321 Support Center',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50003',
            'country' => 'Malaysia',
            'phone_number' => '+60123456792',
        ]);

        $this->command->info('User Module seeded successfully!');
        $this->command->info('Created: 1 Administrator, 1 Gate Staff, 1 Counter Staff, 1 Support Staff');
    }
}
