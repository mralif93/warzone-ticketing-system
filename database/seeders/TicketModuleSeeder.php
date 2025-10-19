<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketModuleSeeder extends Seeder
{
    /**
     * Run the database seeds for Ticket Module.
     */
    public function run(): void
    {
        $this->command->info('Seeding Ticket Module...');

        // Clear existing tickets to prevent duplicates
        $this->command->info('Clearing existing tickets...');
        Ticket::query()->delete();

        $events = Event::all();
        
        if ($events->isEmpty()) {
            $this->command->error('No events found. Please run EventModuleSeeder first.');
            return;
        }

        foreach ($events as $event) {
            $ticketTypes = $this->getTicketTypesForEvent($event);
            
            foreach ($ticketTypes as $ticketTypeData) {
                // Simulate different sales for multi-day events to show day-by-day differences
                $totalSeats = $ticketTypeData['total_seats'];
                $soldSeats = 0;
                $availableSeats = $totalSeats;
                
                // For Music Festival (multi-day event), simulate some sales to show different availability
                if ($event->name === 'Music Festival 2024' && $event->isMultiDay()) {
                    // Simulate different sales patterns for different ticket types
                    if (str_contains($ticketTypeData['name'], 'VIP 2-Day')) {
                        $soldSeats = 6; // 6 sold out of 200
                    } elseif (str_contains($ticketTypeData['name'], 'Single Day VIP')) {
                        $soldSeats = 16; // 16 sold out of 300
                    } elseif (str_contains($ticketTypeData['name'], 'Single Day General')) {
                        $soldSeats = 14; // 14 sold out of 1000
                    } elseif (str_contains($ticketTypeData['name'], 'Premium 2-Day')) {
                        $soldSeats = 25; // 25 sold out of 500
                    } elseif (str_contains($ticketTypeData['name'], 'General 2-Day')) {
                        $soldSeats = 150; // 150 sold out of 2000
                    }
                    
                    $availableSeats = $totalSeats - $soldSeats;
                }
                
                // For other multi-day events, simulate some sales
                elseif ($event->isMultiDay() && $event->name !== 'Warzone Championship Finals 2024') {
                    $soldSeats = rand(5, 20); // Random sales between 5-20
                    $availableSeats = $totalSeats - $soldSeats;
                }
                
                Ticket::create([
                    'event_id' => $event->id,
                    'name' => $ticketTypeData['name'],
                    'price' => $ticketTypeData['price'],
                    'total_seats' => $totalSeats,
                    'available_seats' => $availableSeats,
                    'sold_seats' => $soldSeats,
                    'scanned_seats' => 0,
                    'status' => 'active',
                    'description' => $ticketTypeData['description'],
                    'is_combo' => $ticketTypeData['is_combo'] ?? false,
                ]);
            }
        }

        $this->command->info('Ticket Module seeded successfully!');
        $this->command->info('Created ticket types for all events');
    }

    /**
     * Get ticket types configuration for each event
     */
    private function getTicketTypesForEvent(Event $event): array
    {
        // Check if this is a multi-day event
        $isMultiDay = $event->start_date && $event->end_date && 
                     $event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d');

        // Warzone Championship Finals - Premium gaming event
        if ($event->name === 'Warzone Championship Finals 2024') {
            return [
                [
                    'name' => 'Warzone Exclusive',
                    'price' => 350.00,
                    'total_seats' => 100,
                    'description' => 'Exclusive VIP experience with premium seating, backstage access, and meet & greet with players (50 seats reserved)',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Warzone VIP',
                    'price' => 250.00,
                    'total_seats' => 28,
                    'description' => 'VIP seating with premium services, exclusive merchandise, and priority access',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Warzone Grandstand',
                    'price' => 220.00,
                    'total_seats' => 60,
                    'description' => 'Premium grandstand seating with excellent views and comfortable seating',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Warzone Premium Ringside',
                    'price' => 199.00,
                    'total_seats' => 1716,
                    'description' => 'Premium ringside seating close to the action with great visibility',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Level 1 Zone A/B/C/D',
                    'price' => 129.00,
                    'total_seats' => 1946,
                    'description' => 'Level 1 seating with good views of the main stage and comfortable seating',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Level 2 Zone A/B/C/D',
                    'price' => 89.00,
                    'total_seats' => 1682,
                    'description' => 'Level 2 seating with elevated views and good sightlines',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Standing Zone A/B',
                    'price' => 49.00,
                    'total_seats' => 300,
                    'description' => 'General admission standing area with access to food and beverage vendors',
                    'is_combo' => true,
                ],
            ];
        }

        // Music Festival - Multi-day event with combo options
        if ($event->name === 'Music Festival 2024' && $isMultiDay) {
            return [
                [
                    'name' => 'VIP 2-Day Pass',
                    'price' => 450.00,
                    'total_seats' => 200,
                    'description' => 'VIP access for both days with premium amenities, exclusive areas, and priority access',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Premium 2-Day Pass',
                    'price' => 300.00,
                    'total_seats' => 500,
                    'description' => 'Premium access for both days with good views and comfortable seating',
                    'is_combo' => true,
                ],
                [
                    'name' => 'General 2-Day Pass',
                    'price' => 180.00,
                    'total_seats' => 2000,
                    'description' => 'General admission for both days with access to all stages and vendors',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Single Day VIP',
                    'price' => 250.00,
                    'total_seats' => 300,
                    'description' => 'VIP access for single day with premium amenities',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Single Day General',
                    'price' => 100.00,
                    'total_seats' => 1000,
                    'description' => 'General admission for single day with access to all stages',
                    'is_combo' => false,
                ],
            ];
        }

        // Tech Conference - Multi-day event with combo options
        if ($event->name === 'Tech Conference 2024' && $isMultiDay) {
            return [
                [
                    'name' => 'All Access Pass',
                    'price' => 800.00,
                    'total_seats' => 100,
                    'description' => 'Full access to all sessions, workshops, networking events, and exclusive materials',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Professional Pass',
                    'price' => 500.00,
                    'total_seats' => 300,
                    'description' => 'Access to main sessions, workshops, and networking events',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Student Pass',
                    'price' => 200.00,
                    'total_seats' => 500,
                    'description' => 'Discounted access for students with valid ID required',
                    'is_combo' => true,
                ],
                [
                    'name' => 'Single Day Pass',
                    'price' => 150.00,
                    'total_seats' => 200,
                    'description' => 'Access for single day only with session materials',
                    'is_combo' => false,
                ],
            ];
        }

        // Concert Series - Single day premium event
        if ($event->name === 'Concert Series: Rock Legends') {
            return [
                [
                    'name' => 'VIP Front Row',
                    'price' => 300.00,
                    'total_seats' => 50,
                    'description' => 'Front row VIP seating with meet & greet opportunity',
                    'is_combo' => false,
                ],
                [
                    'name' => 'VIP Premium',
                    'price' => 200.00,
                    'total_seats' => 200,
                    'description' => 'VIP seating with premium services and exclusive merchandise',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Premium Seating',
                    'price' => 150.00,
                    'total_seats' => 500,
                    'description' => 'Premium seating with excellent views and comfortable chairs',
                    'is_combo' => false,
                ],
                [
                    'name' => 'General Admission',
                    'price' => 80.00,
                    'total_seats' => 2000,
                    'description' => 'General admission with standing room and access to vendors',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Student Discount',
                    'price' => 50.00,
                    'total_seats' => 300,
                    'description' => 'Discounted tickets for students with valid ID required',
                    'is_combo' => false,
                ],
            ];
        }

        // Comedy Night - Intimate venue
        if ($event->name === 'Comedy Night Special') {
            return [
                [
                    'name' => 'VIP Table',
                    'price' => 120.00,
                    'total_seats' => 20,
                    'description' => 'VIP table seating with bottle service and meet & greet',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Premium Seating',
                    'price' => 80.00,
                    'total_seats' => 100,
                    'description' => 'Premium seating with excellent views and table service',
                    'is_combo' => false,
                ],
                [
                    'name' => 'General Admission',
                    'price' => 50.00,
                    'total_seats' => 300,
                    'description' => 'General admission with bar seating and standing room',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Student Special',
                    'price' => 30.00,
                    'total_seats' => 50,
                    'description' => 'Student discount with valid ID required',
                    'is_combo' => false,
                ],
            ];
        }

        // Sports Championship - Stadium event
        if ($event->name === 'Sports Championship') {
            return [
                [
                    'name' => 'VIP Box',
                    'price' => 200.00,
                    'total_seats' => 50,
                    'description' => 'VIP box seating with catering and exclusive access',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Premium Seating',
                    'price' => 120.00,
                    'total_seats' => 500,
                    'description' => 'Premium seating with excellent views and comfortable seating',
                    'is_combo' => false,
                ],
                [
                    'name' => 'General Admission',
                    'price' => 60.00,
                    'total_seats' => 2000,
                    'description' => 'General admission with good views and access to concessions',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Student Section',
                    'price' => 30.00,
                    'total_seats' => 200,
                    'description' => 'Student section with valid ID required',
                    'is_combo' => false,
                ],
            ];
        }

        // Gaming Tournament - Gaming center event
        if ($event->name === 'Gaming Tournament') {
            return [
                [
                    'name' => 'VIP Spectator',
                    'price' => 100.00,
                    'total_seats' => 50,
                    'description' => 'VIP spectator area with premium viewing and refreshments',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Premium Viewing',
                    'price' => 60.00,
                    'total_seats' => 200,
                    'description' => 'Premium viewing area with comfortable seating',
                    'is_combo' => false,
                ],
                [
                    'name' => 'General Admission',
                    'price' => 30.00,
                    'total_seats' => 800,
                    'description' => 'General admission with standing room and access to food vendors',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Student Pass',
                    'price' => 20.00,
                    'total_seats' => 100,
                    'description' => 'Student discount with valid ID required',
                    'is_combo' => false,
                ],
            ];
        }

        // Art Exhibition - Gallery event
        if ($event->name === 'Art Exhibition Opening') {
            return [
                [
                    'name' => 'VIP Opening',
                    'price' => 80.00,
                    'total_seats' => 30,
                    'description' => 'VIP opening night with artist meet & greet and exclusive access',
                    'is_combo' => false,
                ],
                [
                    'name' => 'Premium Access',
                    'price' => 50.00,
                    'total_seats' => 100,
                    'description' => 'Premium access with guided tour and refreshments',
                    'is_combo' => false,
                ],
                [
                    'name' => 'General Admission',
                    'price' => 25.00,
                    'total_seats' => 150,
                    'description' => 'General admission with self-guided tour',
                    'is_combo' => false,
                ],
            ];
        }

        // Default ticket types for other events
        return [
            [
                'name' => 'VIP',
                'price' => 150.00,
                'total_seats' => 100,
                'description' => 'VIP seating with premium services and exclusive access',
                'is_combo' => false,
            ],
            [
                'name' => 'Premium',
                'price' => 100.00,
                'total_seats' => 300,
                'description' => 'Premium seating with good views and comfortable seating',
                'is_combo' => false,
            ],
            [
                'name' => 'General',
                'price' => 50.00,
                'total_seats' => 500,
                'description' => 'General admission seating with access to basic amenities',
                'is_combo' => false,
            ],
        ];
    }
}
