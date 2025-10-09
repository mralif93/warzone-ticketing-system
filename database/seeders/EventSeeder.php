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
                'status' => 'On Sale',
                'max_tickets_per_order' => 8,
                'description' => 'The ultimate championship finals featuring the best teams in the world.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Concert Series: Rock Legends',
                'date_time' => now()->addDays(45)->setTime(20, 0),
                'status' => 'On Sale',
                'max_tickets_per_order' => 6,
                'description' => 'An unforgettable night with legendary rock artists.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Tech Conference 2024',
                'date_time' => now()->addDays(60)->setTime(9, 0),
                'status' => 'Draft',
                'max_tickets_per_order' => 10,
                'description' => 'The biggest technology conference of the year.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Comedy Night Special',
                'date_time' => now()->addDays(15)->setTime(21, 0),
                'status' => 'Sold Out',
                'max_tickets_per_order' => 4,
                'description' => 'An evening of laughter with top comedians.',
                'venue' => 'Warzone Arena',
            ],
            [
                'name' => 'Sports Championship',
                'date_time' => now()->addDays(90)->setTime(18, 30),
                'status' => 'On Sale',
                'max_tickets_per_order' => 8,
                'description' => 'Championship game featuring the top teams.',
                'venue' => 'Warzone Arena',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}