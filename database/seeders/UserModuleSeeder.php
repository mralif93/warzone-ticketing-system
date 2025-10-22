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
        $customers = [
            [
                'name' => 'Ahmad Rahman',
                'email' => 'ahmad.rahman@email.com',
                'phone' => '+60123456793',
                'address_line_1' => '100 Jalan Ampang',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50450',
                'country' => 'Malaysia',
                'phone_number' => '+60123456793',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'phone' => '+60123456794',
                'address_line_1' => '200 Jalan Bukit Bintang',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50200',
                'country' => 'Malaysia',
                'phone_number' => '+60123456794',
            ],
            [
                'name' => 'Muhammad Ali',
                'email' => 'muhammad.ali@email.com',
                'phone' => '+60123456795',
                'address_line_1' => '300 Jalan Sultan Ismail',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50200',
                'country' => 'Malaysia',
                'phone_number' => '+60123456795',
            ],
            [
                'name' => 'Fatimah Zahra',
                'email' => 'fatimah.zahra@email.com',
                'phone' => '+60123456796',
                'address_line_1' => '400 Jalan Pudu',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '55100',
                'country' => 'Malaysia',
                'phone_number' => '+60123456796',
            ],
            [
                'name' => 'Hassan Abdullah',
                'email' => 'hassan.abdullah@email.com',
                'phone' => '+60123456797',
                'address_line_1' => '500 Jalan Cheras',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '43200',
                'country' => 'Malaysia',
                'phone_number' => '+60123456797',
            ],
            [
                'name' => 'Aisha Mohamed',
                'email' => 'aisha.mohamed@email.com',
                'phone' => '+60123456798',
                'address_line_1' => '600 Jalan Klang',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50000',
                'country' => 'Malaysia',
                'phone_number' => '+60123456798',
            ],
            [
                'name' => 'Omar Hassan',
                'email' => 'omar.hassan@email.com',
                'phone' => '+60123456799',
                'address_line_1' => '700 Jalan Gombak',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '53000',
                'country' => 'Malaysia',
                'phone_number' => '+60123456799',
            ],
            [
                'name' => 'Nurul Izzah',
                'email' => 'nurul.izzah@email.com',
                'phone' => '+60123456800',
                'address_line_1' => '800 Jalan Setapak',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '53000',
                'country' => 'Malaysia',
                'phone_number' => '+60123456800',
            ],
            [
                'name' => 'Ibrahim Yusuf',
                'email' => 'ibrahim.yusuf@email.com',
                'phone' => '+60123456801',
                'address_line_1' => '900 Jalan Sentul',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '51000',
                'country' => 'Malaysia',
                'phone_number' => '+60123456801',
            ],
            [
                'name' => 'Zainab Ali',
                'email' => 'zainab.ali@email.com',
                'phone' => '+60123456802',
                'address_line_1' => '1000 Jalan Duta',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50480',
                'country' => 'Malaysia',
                'phone_number' => '+60123456802',
            ],
        ];

        foreach ($customers as $customerData) {
            User::create(array_merge($customerData, [
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'customer',
                'is_active' => true,
            ]));
        }

        // Create unverified customer for testing
        User::create([
            'name' => 'Unverified Customer',
            'email' => 'unverified@email.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
            'role' => 'customer',
            'is_active' => false,
            'phone' => '+60123456803',
            'address_line_1' => '1100 Jalan Test',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postcode' => '50000',
            'country' => 'Malaysia',
            'phone_number' => '+60123456803',
        ]);

        $this->command->info('User Module seeded successfully!');
        $this->command->info('Created: 4 staff members + 10 customers + 1 unverified customer');
    }
}
