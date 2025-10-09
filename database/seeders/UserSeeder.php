<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Administrator',
            'address_line_1' => '123 Admin Street',
            'city' => 'Admin City',
            'state' => 'AC',
            'postcode' => '12345',
            'country' => 'USA',
            'phone_number' => '555-0001',
        ]);

        // Create gate staff
        User::create([
            'name' => 'Gate Staff',
            'email' => 'gate@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Gate Staff',
            'address_line_1' => '456 Gate Avenue',
            'city' => 'Staff City',
            'state' => 'SC',
            'postcode' => '54321',
            'country' => 'USA',
            'phone_number' => '555-0002',
        ]);

        // Create counter staff
        User::create([
            'name' => 'Counter Staff',
            'email' => 'counter@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Counter Staff',
            'address_line_1' => '789 Counter Blvd',
            'city' => 'Staff City',
            'state' => 'SC',
            'postcode' => '54321',
            'country' => 'USA',
            'phone_number' => '555-0003',
        ]);

        // Create support staff
        User::create([
            'name' => 'Support Staff',
            'email' => 'support@warzone.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Support Staff',
            'address_line_1' => '321 Support Lane',
            'city' => 'Staff City',
            'state' => 'SC',
            'postcode' => '54321',
            'country' => 'USA',
            'phone_number' => '555-0004',
        ]);

        // Create regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
            'address_line_1' => '100 Customer Street',
            'city' => 'Customer City',
            'state' => 'CC',
            'postcode' => '11111',
            'country' => 'USA',
            'phone_number' => '555-1001',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah.wilson@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'David Brown',
            'email' => 'david.brown@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'Lisa Davis',
            'email' => 'lisa.davis@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'Tom Miller',
            'email' => 'tom.miller@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        User::create([
            'name' => 'Emma Garcia',
            'email' => 'emma.garcia@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'Customer',
        ]);

        // Create unverified user for testing
        User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
            'role' => 'Customer',
        ]);
    }
}
