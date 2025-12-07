<?php
/**
 * Run this script on production to find which orders caused the overselling
 * Usage: php artisan tinker < check_oversold_orders.php
 */

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

$event = Event::first();
$eventDays = $event->getEventDays();
$day2Date = $eventDays[1]['date'] ?? null;

echo "=== OVERSELLING INVESTIGATION REPORT ===\n";
echo "Day 2 Date: {$day2Date}\n\n";

// Get Standing Zone ticket (id = 1 based on diagnostic)
$standingZone = Ticket::find(1);
$maxSeats = $standingZone->total_seats ?? 340;

echo "Standing Zone: {$standingZone->name}\n";
echo "Max Seats per Day: {$maxSeats}\n\n";

// Query all Standing Zone Day 2 tickets with their orders
$tickets = DB::table('customer_tickets')
    ->join('orders', 'customer_tickets.order_id', '=', 'orders.id')
    ->where('customer_tickets.ticket_id', 1)
    ->whereDate('customer_tickets.event_day', $day2Date)
    ->whereIn('customer_tickets.status', ['active', 'sold', 'scanned', 'pending', 'Active', 'Sold', 'Scanned', 'Pending'])
    ->whereNull('customer_tickets.deleted_at')
    ->select([
        'orders.id as order_id',
        'orders.order_number',
        'orders.customer_email',
        'orders.status as order_status',
        'orders.created_at',
        'orders.payment_method',
        DB::raw('COUNT(*) as ticket_count')
    ])
    ->groupBy('orders.id', 'orders.order_number', 'orders.customer_email', 'orders.status', 'orders.created_at', 'orders.payment_method')
    ->orderBy('orders.created_at')
    ->get();

$runningTotal = 0;
$oversoldOrders = [];

echo "=== ORDER TIMELINE (Standing Zone Day 2) ===\n";
echo sprintf("%-6s | %-15s | %-30s | %-10s | %-4s | %-6s | %-8s\n", 
    'ID', 'Order #', 'Email', 'Status', 'Qty', 'Total', 'Oversold');
echo str_repeat('-', 100) . "\n";

foreach ($tickets as $t) {
    $runningTotal += $t->ticket_count;
    $isOversold = $runningTotal > $maxSeats;
    
    echo sprintf("%-6s | %-15s | %-30s | %-10s | %-4s | %-6s | %-8s\n",
        $t->order_id,
        $t->order_number,
        substr($t->customer_email, 0, 30),
        $t->order_status,
        $t->ticket_count,
        $runningTotal,
        $isOversold ? '⚠️ YES' : 'No'
    );
    
    if ($isOversold) {
        $oversoldOrders[] = $t;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total Orders: " . count($tickets) . "\n";
echo "Total Tickets Sold: {$runningTotal}\n";
echo "Max Seats: {$maxSeats}\n";
echo "Oversold By: " . max(0, $runningTotal - $maxSeats) . " tickets\n";

if (count($oversoldOrders) > 0) {
    echo "\n=== OVERSOLD ORDERS (Need Attention) ===\n";
    echo "These " . count($oversoldOrders) . " orders were placed AFTER capacity was reached:\n\n";
    
    foreach ($oversoldOrders as $o) {
        echo "Order #{$o->order_number}\n";
        echo "  - Email: {$o->customer_email}\n";
        echo "  - Tickets: {$o->ticket_count}\n";
        echo "  - Created: {$o->created_at}\n";
        echo "  - Status: {$o->order_status}\n";
        echo "  - Payment: {$o->payment_method}\n";
        echo "\n";
    }
}

