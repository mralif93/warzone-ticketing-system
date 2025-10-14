<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing orders and tickets...');
        DB::table('tickets')->truncate();
        DB::table('orders')->truncate();

        // Get events and users
        $events = Event::all();
        $customers = User::where('role', 'Customer')->get();
        
        if ($events->isEmpty()) {
            $this->command->error('No events found. Please run EventSeeder first.');
            return;
        }
        
        if ($customers->isEmpty()) {
            $this->command->error('No customers found. Please run UserSeeder first.');
            return;
        }

        // Zone pricing configuration
        $zonePrices = [
            'Warzone Exclusive' => 500,
            'Warzone VIP' => 250,
            'Warzone Grandstand' => 199,
            'Warzone Premium Ringside' => 150,
            'Level 1 Zone A/B/C/D' => 100,
            'Level 2 Zone A/B/C/D' => 75,
            'Standing Zone A/B' => 50,
        ];

        $orderCount = 0;
        $ticketCount = 0;
        $usedOrderNumbers = [];

        // Create orders with multiple tickets per order (1 order = multiple tickets of same zone)
        foreach ($events as $event) {
            // Create 3-8 orders per event
            $ordersPerEvent = rand(3, 8);
            
            for ($i = 0; $i < $ordersPerEvent; $i++) {
                $customer = $customers->random();
                $zone = array_rand($zonePrices);
                $basePrice = $zonePrices[$zone];
                
                // Random quantity between 1-10 tickets per order (max 10)
                $quantity = rand(1, 10);
                
                // Generate unique order number
                do {
                    $orderNumber = 'WZ' . date('Ymd') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                } while (in_array($orderNumber, $usedOrderNumbers));
                $usedOrderNumbers[] = $orderNumber;
                
                // Generate QR code for order
                $qrcode = 'ORD' . strtoupper(uniqid()) . rand(1000, 9999);
                
                // Calculate pricing for multiple tickets
                $subtotal = $basePrice * $quantity;
                $serviceFee = $this->calculateServiceFee($subtotal);
                $taxAmount = $this->calculateTax($subtotal + $serviceFee);
                $totalAmount = $subtotal + $serviceFee + $taxAmount;
                
                // Create order with calculated pricing (1 order = multiple tickets)
                $order = Order::create([
                    'user_id' => $customer->id,
                    'customer_email' => $customer->email,
                    'order_number' => $orderNumber,
                    'qrcode' => $qrcode,
                    'subtotal' => $subtotal,
                    'service_fee' => $serviceFee,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'status' => $this->getRandomStatus(),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'notes' => $this->getRandomNotes(),
                ]);

                // Create multiple tickets for this order (same zone)
                for ($j = 0; $j < $quantity; $j++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'zone' => $zone,
                        'qrcode' => \App\Models\Ticket::generateQRCode(),
                        'status' => 'Sold',
                        'price_paid' => $basePrice,
                    ]);
                    $ticketCount++;
                }
                
                $orderCount++;
            }
        }

        $this->command->info("Successfully created {$orderCount} orders and {$ticketCount} tickets!");
        $this->command->info("Average tickets per order: " . round($ticketCount / $orderCount, 2));
        $this->command->info('All orders and tickets are properly synchronized.');
    }


    /**
     * Get random order status
     */
    private function getRandomStatus()
    {
        $statuses = ['Paid', 'Paid', 'Paid', 'Pending', 'Cancelled']; // More likely to be Paid
        return $statuses[array_rand($statuses)];
    }

    /**
     * Get random payment method
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['Credit Card', 'Debit Card', 'Online Banking', 'E-Wallet', 'Cash'];
        return $methods[array_rand($methods)];
    }

    /**
     * Get random notes
     */
    private function getRandomNotes()
    {
        $notes = [
            null,
            'Customer requested front row seats',
            'Group booking for company event',
            'Special dietary requirements noted',
            'VIP package upgrade requested',
            'Early bird discount applied',
            'Corporate booking',
            'Family package',
            'Student discount applied',
            'Bulk purchase for team building',
        ];
        return $notes[array_rand($notes)];
    }

    /**
     * Calculate service fee (5% of subtotal)
     */
    private function calculateServiceFee($subtotal)
    {
        return round($subtotal * 0.05, 2);
    }

    /**
     * Calculate tax (6% of subtotal + service fee)
     */
    private function calculateTax($amount)
    {
        return round($amount * 0.06, 2);
    }
}
