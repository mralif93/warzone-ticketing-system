<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * This seeder follows the "1 module = 1 seeder" approach:
     * - UserModuleSeeder: Creates users (admin, staff, customers)
     * - EventModuleSeeder: Creates events with multi-day support
     * - TicketModuleSeeder: Creates ticket types/zones for each event
     * - OrderModuleSeeder: Creates orders and purchase tickets
     * - SystemModuleSeeder: Creates audit logs and system data
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding process...');
        $this->command->info('Following 1 module = 1 seeder approach');
        $this->command->newLine();

        $this->call([
            UserModuleSeeder::class,      // Module 1: User Management
            EventModuleSeeder::class,     // Module 2: Event Management
            TicketModuleSeeder::class,    // Module 3: Ticket Management
            OrderModuleSeeder::class,     // Module 4: Order Management
            SystemModuleSeeder::class,    // Module 5: System Management
        ]);

        $this->command->newLine();
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('All modules have been seeded with test data.');
    }
}
