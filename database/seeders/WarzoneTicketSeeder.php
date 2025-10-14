<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;

class WarzoneTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Warzone Championship event
        $event = Event::where('name', 'Warzone Championship Finals 2024')->first();
        
        if (!$event) {
            $this->command->error('Warzone Championship Finals 2024 event not found. Please run EventSeeder first.');
            return;
        }

        // Create a sample user for orders
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Sample Customer',
                'email' => 'customer@warzone.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]);
        }

        // Create sample orders
        $orders = [
            [
                'user_id' => $user->id,
                'customer_email' => 'customer@warzone.com',
                'order_number' => 'WZ-2024-001',
                'subtotal' => 350.00,
                'service_fee' => 17.50,
                'tax_amount' => 29.40,
                'total_amount' => 396.90,
                'status' => 'Paid',
                'payment_method' => 'Credit Card',
            ],
            [
                'user_id' => $user->id,
                'customer_email' => 'customer@warzone.com',
                'order_number' => 'WZ-2024-002',
                'subtotal' => 500.00, // 2x VIP tickets
                'service_fee' => 25.00,
                'tax_amount' => 42.00,
                'total_amount' => 567.00,
                'status' => 'Paid',
                'payment_method' => 'Credit Card',
            ],
            [
                'user_id' => $user->id,
                'customer_email' => 'customer@warzone.com',
                'order_number' => 'WZ-2024-003',
                'subtotal' => 129.00,
                'service_fee' => 6.45,
                'tax_amount' => 10.84,
                'total_amount' => 146.29,
                'status' => 'Paid',
                'payment_method' => 'Credit Card',
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create($orderData);
            
            // Create tickets based on order
            switch ($order->order_number) {
                case 'WZ-2024-001':
                    // Warzone Exclusive ticket
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'zone' => 'Warzone Exclusive',
                        'qrcode' => 'WZEX' . rand(1000, 9999),
                        'status' => 'Sold',
                        'price_paid' => 350.00,
                    ]);
                    break;
                    
                case 'WZ-2024-002':
                    // 2x Warzone VIP tickets
                    for ($i = 0; $i < 2; $i++) {
                        Ticket::create([
                            'order_id' => $order->id,
                            'event_id' => $event->id,
                            'zone' => 'Warzone VIP',
                            'qrcode' => 'WZVIP' . rand(1000, 9999),
                            'status' => 'Sold',
                            'price_paid' => 250.00,
                        ]);
                    }
                    break;
                    
                case 'WZ-2024-003':
                    // Level 1 Zone ticket
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'zone' => 'Level 1 Zone A/B/C/D',
                        'qrcode' => 'WZL1' . rand(1000, 9999),
                        'status' => 'Sold',
                        'price_paid' => 129.00,
                    ]);
                    break;
            }
        }

        // Create additional sample tickets for each category
        $categories = [
            'Warzone Grandstand' => 220.00,
            'Warzone Premium Ringside' => 199.00,
            'Level 2 Zone A/B/C/D' => 89.00,
            'Standing Zone A/B' => 49.00,
        ];

        foreach ($categories as $category => $price) {
            $order = Order::create([
                'user_id' => $user->id,
                'customer_email' => 'customer@warzone.com',
                'order_number' => 'WZ-2024-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                'subtotal' => $price,
                'service_fee' => $price * 0.05,
                'tax_amount' => ($price + ($price * 0.05)) * 0.08,
                'total_amount' => $price + ($price * 0.05) + (($price + ($price * 0.05)) * 0.08),
                'status' => 'Paid',
                'payment_method' => 'Credit Card',
            ]);

            Ticket::create([
                'order_id' => $order->id,
                'event_id' => $event->id,
                'zone' => $category,
                'qrcode' => 'WZ' . strtoupper(substr(str_replace(' ', '', $category), 0, 4)) . rand(1000, 9999),
                'status' => 'Sold',
                'price_paid' => $price,
            ]);
        }

        $this->command->info('Warzone ticket categories seeded successfully!');
        $this->command->info('Created tickets for all 7 categories:');
        $this->command->info('- Warzone Exclusive (RM350)');
        $this->command->info('- Warzone VIP (RM250)');
        $this->command->info('- Warzone Grandstand (RM220)');
        $this->command->info('- Warzone Premium Ringside (RM199)');
        $this->command->info('- Level 1 Zone A/B/C/D (RM129)');
        $this->command->info('- Level 2 Zone A/B/C/D (RM89)');
        $this->command->info('- Standing Zone A/B (RM49)');
    }
}