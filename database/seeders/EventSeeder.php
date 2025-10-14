<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Warzone Championship Finals 2024',
                'date_time' => now()->addDays(30)->setTime(19, 0),
                'start_date' => now()->addDays(30)->setTime(19, 0),
                'end_date' => now()->addDays(30)->setTime(23, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 10,
                'total_seats' => 5882, // Exact total from your table
                'description' => 'The ultimate championship finals featuring the best teams in the world. 7 ticket categories available.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Concert Series: Rock Legends',
                'date_time' => now()->addDays(45)->setTime(20, 0),
                'start_date' => now()->addDays(45)->setTime(20, 0),
                'end_date' => now()->addDays(45)->setTime(23, 30),
                'status' => 'On Sale',
                'max_tickets_per_order' => 10,
                'total_seats' => 5000,
                'description' => 'An unforgettable night with legendary rock artists.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Tech Conference 2024',
                'date_time' => now()->addDays(60)->setTime(9, 0),
                'start_date' => now()->addDays(60)->setTime(9, 0),
                'end_date' => now()->addDays(62)->setTime(17, 0),
                'status' => 'Draft',
                'max_tickets_per_order' => 10,
                'total_seats' => 3000,
                'description' => 'The biggest technology conference of the year - 3 days of innovation and networking.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Comedy Night Special',
                'date_time' => now()->addDays(15)->setTime(21, 0),
                'start_date' => null,
                'end_date' => null,
                'status' => 'Sold Out',
                'max_tickets_per_order' => 10,
                'total_seats' => 800,
                'description' => 'An evening of laughter with top comedians.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Sports Championship',
                'date_time' => now()->addDays(90)->setTime(18, 30),
                'start_date' => now()->addDays(90)->setTime(18, 30),
                'end_date' => now()->addDays(90)->setTime(22, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 10,
                'total_seats' => 4000,
                'description' => 'Championship game featuring the top teams.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Music Festival 2024',
                'date_time' => now()->addDays(120)->setTime(12, 0),
                'start_date' => now()->addDays(120)->setTime(12, 0),
                'end_date' => now()->addDays(122)->setTime(23, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 10,
                'total_seats' => 10000,
                'description' => '3-day music festival featuring international artists and local talents.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Gaming Tournament',
                'date_time' => now()->addDays(75)->setTime(10, 0),
                'start_date' => null,
                'end_date' => null,
                'status' => 'On Sale',
                'max_tickets_per_order' => 10,
                'total_seats' => 2000,
                'description' => 'Single-day gaming tournament with cash prizes.',
                'venue' => 'Warzone Arena',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}