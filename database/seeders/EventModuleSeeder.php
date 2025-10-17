<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventModuleSeeder extends Seeder
{
    /**
     * Run the database seeds for Event Module.
     */
    public function run(): void
    {
        $this->command->info('Seeding Event Module...');

        $events = [
            [
                'name' => 'Warzone Championship Finals 2024',
                'date_time' => now()->addDays(30)->setTime(19, 0),
                'start_date' => now()->addDays(30)->setTime(19, 0),
                'end_date' => now()->addDays(30)->setTime(23, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 8,
                'total_seats' => 7000,
                'description' => 'The ultimate championship finals featuring the best teams in the world. Experience the most intense gaming competition with premium viewing and exclusive access.',
                'venue' => 'Warzone Arena, Kuala Lumpur',
                'combo_discount_percentage' => 10.00,
                'combo_discount_enabled' => true,
                'default' => false, // Set as default event
            ],
            [
                'name' => 'Concert Series: Rock Legends',
                'date_time' => now()->addDays(45)->setTime(20, 0),
                'start_date' => now()->addDays(45)->setTime(20, 0),
                'end_date' => now()->addDays(45)->setTime(23, 30),
                'status' => 'On Sale',
                'max_tickets_per_order' => 6,
                'total_seats' => 5000,
                'description' => 'An unforgettable night with legendary rock artists performing their greatest hits. A once-in-a-lifetime musical experience.',
                'venue' => 'Warzone Arena, Kuala Lumpur',
                'combo_discount_percentage' => 0.00,
                'combo_discount_enabled' => false,
                'default' => false,
            ],
            [
                'name' => 'Tech Conference 2024',
                'date_time' => now()->addDays(60)->setTime(9, 0),
                'start_date' => now()->addDays(60)->setTime(9, 0),
                'end_date' => now()->addDays(61)->setTime(17, 0),
                'status' => 'Draft',
                'max_tickets_per_order' => 10,
                'total_seats' => 2000,
                'description' => 'The biggest technology conference of the year - 2 days of innovation, networking, and cutting-edge technology presentations.',
                'venue' => 'Warzone Convention Center, Kuala Lumpur',
                'combo_discount_percentage' => 15.00,
                'combo_discount_enabled' => true,
                'default' => false,
            ],
            [
                'name' => 'Comedy Night Special',
                'date_time' => now()->addDays(15)->setTime(21, 0),
                'start_date' => now()->addDays(15)->setTime(21, 0),
                'end_date' => now()->addDays(15)->setTime(23, 0),
                'status' => 'Sold Out',
                'max_tickets_per_order' => 4,
                'total_seats' => 800,
                'description' => 'An evening of laughter with top comedians from around the region. Guaranteed to have you rolling in the aisles.',
                'venue' => 'Warzone Comedy Club, Kuala Lumpur',
                'combo_discount_percentage' => 0.00,
                'combo_discount_enabled' => false,
                'default' => false,
            ],
            [
                'name' => 'Sports Championship',
                'date_time' => now()->addDays(90)->setTime(18, 30),
                'start_date' => now()->addDays(90)->setTime(18, 30),
                'end_date' => now()->addDays(90)->setTime(22, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 8,
                'total_seats' => 4000,
                'description' => 'Championship game featuring the top teams. Witness history in the making with the most exciting sports action.',
                'venue' => 'Warzone Stadium, Kuala Lumpur',
                'combo_discount_percentage' => 0.00,
                'combo_discount_enabled' => false,
                'default' => false,
            ],
            [
                'name' => 'Music Festival 2024',
                'date_time' => '2026-02-14 12:00:00',
                'start_date' => '2026-02-14 12:00:00',
                'end_date' => '2026-02-15 23:00:00',
                'status' => 'On Sale',
                'max_tickets_per_order' => 6,
                'total_seats' => 8000,
                'description' => '2-day music festival featuring international artists and local talents. A celebration of music, culture, and community.',
                'venue' => 'Warzone Festival Grounds, Kuala Lumpur',
                'combo_discount_percentage' => 20.00,
                'combo_discount_enabled' => true,
                'default' => false,
            ],
            [
                'name' => 'Gaming Tournament',
                'date_time' => now()->addDays(75)->setTime(10, 0),
                'start_date' => now()->addDays(75)->setTime(10, 0),
                'end_date' => now()->addDays(75)->setTime(18, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 5,
                'total_seats' => 1500,
                'description' => 'Single-day gaming tournament with cash prizes. Watch the best gamers compete in various popular games.',
                'venue' => 'Warzone Gaming Center, Kuala Lumpur',
                'combo_discount_percentage' => 0.00,
                'combo_discount_enabled' => false,
                'default' => false,
            ],
            [
                'name' => 'Art Exhibition Opening',
                'date_time' => now()->addDays(20)->setTime(18, 0),
                'start_date' => now()->addDays(20)->setTime(18, 0),
                'end_date' => now()->addDays(20)->setTime(22, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 4,
                'total_seats' => 300,
                'description' => 'Exclusive opening of the contemporary art exhibition featuring works from renowned local and international artists.',
                'venue' => 'Warzone Art Gallery, Kuala Lumpur',
                'combo_discount_percentage' => 0.00,
                'combo_discount_enabled' => false,
                'default' => false,
            ],
        ];

        foreach ($events as $eventData) {
            // Ensure default field is set for all events
            if (!isset($eventData['default'])) {
                $eventData['default'] = false;
            }
            Event::create($eventData);
        }

        $this->command->info('Event Module seeded successfully!');
        $this->command->info('Created: 8 events with various statuses and configurations');
    }
}
