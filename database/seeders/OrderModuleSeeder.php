<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;
use App\Models\PurchaseTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderModuleSeeder extends Seeder
{
    /**
     * Run the database seeds for Order Module.
     */
    public function run(): void
    {
        $this->command->info('Seeding Order Module...');

        // Clear existing data
        $this->command->info('Clearing existing orders and purchase tickets...');
        PurchaseTicket::query()->delete();
        Order::query()->delete();

        // Get events, ticket types, and users
        $events = Event::all();
        $customers = User::where('role', 'customer')->get();
        
        if ($events->isEmpty()) {
            $this->command->error('No events found. Please run EventModuleSeeder first.');
            return;
        }
        
        if ($customers->isEmpty()) {
            $this->command->error('No customers found. Please run UserModuleSeeder first.');
            return;
        }

        $orderCount = 0;
        $ticketCount = 0;
        $usedOrderNumbers = [];

        // Create orders with purchase tickets
        foreach ($events as $event) {
            $ticketTypes = Ticket::where('event_id', $event->id)->get();
            
            if ($ticketTypes->isEmpty()) {
                $this->command->warn("No ticket types found for event: {$event->name}");
                continue;
            }

            // Create more orders for Music Festival to show realistic sales patterns
            $ordersPerEvent = ($event->name === 'Music Festival 2024') ? rand(25, 40) : rand(8, 20);
            
            for ($i = 0; $i < $ordersPerEvent; $i++) {
                $customer = $customers->random();
                $ticketType = $ticketTypes->random();
                
                // Random quantity between 1-6 tickets per order
                $quantity = rand(1, min(6, $ticketType->available_seats));
                
                // Generate unique order number
                do {
                    $orderNumber = 'WZ' . date('Ymd') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . '-' . uniqid();
                } while (in_array($orderNumber, $usedOrderNumbers));
                $usedOrderNumbers[] = $orderNumber;
                
                // Generate QR code for order
                $qrcode = 'ORD' . strtoupper(uniqid()) . rand(1000, 9999);
                
                // Calculate pricing
                $subtotal = $ticketType->price * $quantity;
                $serviceFee = $this->calculateServiceFee($subtotal);
                $taxAmount = $this->calculateTax($subtotal + $serviceFee);
                $totalAmount = $subtotal + $serviceFee + $taxAmount;
                
                // Check if this is a multi-day event
                $isMultiDay = $event->start_date && $event->end_date && 
                             $event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d');
                
                // Determine if this is a combo purchase (for multi-day events)
                $isComboPurchase = $isMultiDay && $ticketType->is_combo;
                $comboGroupId = $isComboPurchase ? 'COMBO_' . uniqid() : null;
                
                // Create order
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

                // Create purchase tickets
                for ($j = 0; $j < $quantity; $j++) {
                    $eventDay = null;
                    $eventDayName = null;
                    
                    // For multi-day events, assign specific days
                    if ($isMultiDay) {
                        if ($isComboPurchase) {
                            // Combo tickets get both days
                            $eventDay = $event->start_date->format('Y-m-d');
                            $eventDayName = 'All Days';
                        } else {
                            // Single day tickets - make Day 1 more popular (70% vs 30%)
                            $days = $this->getEventDays($event);
                            if (count($days) >= 2) {
                                // 70% chance for Day 1, 30% chance for Day 2
                                $dayChoice = (rand(1, 10) <= 7) ? 0 : 1;
                                $randomDay = $days[$dayChoice];
                            } else {
                                $randomDay = $days[array_rand($days)];
                            }
                            $eventDay = $randomDay['date'];
                            $eventDayName = $randomDay['name'];
                        }
                    }

                    // Calculate discount for combo purchases
                    $originalPrice = $ticketType->price;
                    $discountAmount = 0;
                    
                    if ($isComboPurchase && $event->combo_discount_enabled) {
                        $discountAmount = $originalPrice * ($event->combo_discount_percentage / 100);
                    }

                    PurchaseTicket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'ticket_type_id' => $ticketType->id,
                        'zone' => $ticketType->name, // Denormalized for compatibility
                        'event_day' => $eventDay,
                        'event_day_name' => $eventDayName,
                        'is_combo_purchase' => $isComboPurchase,
                        'combo_group_id' => $comboGroupId,
                        'original_price' => $originalPrice,
                        'discount_amount' => $discountAmount,
                        'qrcode' => PurchaseTicket::generateQRCode(),
                        'status' => $this->getRandomPurchaseStatus(),
                        'price_paid' => $originalPrice - $discountAmount,
                    ]);
                    $ticketCount++;
                }
                
                // Update ticket type sold seats
                $ticketType->increment('sold_seats', $quantity);
                $ticketType->decrement('available_seats', $quantity);
                
                $orderCount++;
            }
        }

        $this->command->info('Order Module seeded successfully!');
        $this->command->info("Created: {$orderCount} orders and {$ticketCount} purchase tickets");
        $this->command->info("Average tickets per order: " . round($ticketCount / $orderCount, 2));
    }

    /**
     * Get event days for multi-day events
     */
    private function getEventDays(Event $event): array
    {
        if (!$event->start_date || !$event->end_date) {
            return [];
        }

        $days = [];
        $current = $event->start_date->copy();
        $end = $event->end_date->copy();
        $dayNumber = 1;

        while ($current->lte($end)) {
            $days[] = [
                'date' => $current->format('Y-m-d'),
                'name' => "Day {$dayNumber}",
            ];
            $current->addDay();
            $dayNumber++;
        }

        return $days;
    }

    /**
     * Get random order status
     */
    private function getRandomStatus()
    {
        $statuses = ['paid', 'paid', 'paid', 'paid', 'pending', 'cancelled']; // More likely to be paid
        return $statuses[array_rand($statuses)];
    }

    /**
     * Get random payment method
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['bank_transfer', 'online_banking', 'qr_code_ewallet', 'debit_credit_card', 'others'];
        return $methods[array_rand($methods)];
    }

    /**
     * Get random purchase ticket status
     */
    private function getRandomPurchaseStatus()
    {
        $statuses = ['sold', 'scanned', 'held', 'invalid', 'refunded']; // More likely to be sold
        return $statuses[array_rand($statuses)];
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
            'Wheelchair accessible seating requested',
            'Anniversary celebration',
            'Birthday party booking',
            'VIP parking requested',
            'Special occasion celebration',
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
