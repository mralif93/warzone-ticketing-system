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

        // Create Customer Users
        User::create([
            'name' => 'John Doe',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'customer',
            'is_active' => true,
            'phone' => '+60123456793',
            'address_line_1' => '123 Customer Street',
            'address_line_2' => 'Apt 4B',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50004',
            'country' => 'Malaysia',
            'phone_number' => '+60123456793',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'customer',
            'is_active' => true,
            'phone' => '+60123456794',
            'address_line_1' => '456 Customer Avenue',
            'address_line_2' => 'Unit 12',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'postcode' => '47301',
            'country' => 'Malaysia',
            'phone_number' => '+60123456794',
        ]);

        User::create([
            'name' => 'Ahmad Bin Abdullah',
            'email' => 'customer3@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'customer',
            'is_active' => true,
            'phone' => '+60123456795',
            'address_line_1' => '789 Customer Road',
            'city' => 'Shah Alam',
            'state' => 'Selangor',
            'postcode' => '40000',
            'country' => 'Malaysia',
            'phone_number' => '+60123456795',
        ]);

        User::create([
            'name' => 'Sarah Lee',
            'email' => 'customer4@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'customer',
            'is_active' => true,
            'phone' => '+60123456796',
            'address_line_1' => '321 Customer Boulevard',
            'address_line_2' => 'Floor 5',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50005',
            'country' => 'Malaysia',
            'phone_number' => '+60123456796',
        ]);

        User::create([
            'name' => 'Mohammad Ali',
            'email' => 'customer5@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'customer',
            'is_active' => true,
            'phone' => '+60123456797',
            'address_line_1' => '654 Customer Lane',
            'city' => 'Subang Jaya',
            'state' => 'Selangor',
            'postcode' => '47500',
            'country' => 'Malaysia',
            'phone_number' => '+60123456797',
        ]);

        $this->command->info('User Module seeded successfully!');
        $this->command->info('Created: 1 Administrator, 1 Gate Staff, 1 Counter Staff, 1 Support Staff, 5 Customers');
    }
}
