<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-pending {--minutes=15 : Minutes after which to cancel pending orders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending orders from previous days that are older than specified minutes (default: 15 minutes). Does not cancel today\'s orders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        $cutoffTime = Carbon::now()->subMinutes($minutes);
        
        // Find pending orders older than the cutoff time AND from previous days (not today)
        // This ensures we only cancel orders from previous days, not today's orders
        $startOfToday = Carbon::today()->startOfDay();
        
        $pendingOrders = Order::where('status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->where('created_at', '<', $startOfToday) // Only orders from previous days (not today)
            ->get();
        
        $cancelledCount = 0;
        
        foreach ($pendingOrders as $order) {
            try {
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $minutes . ' minutes'
                ]);
                
                // Update purchase ticket statuses to cancelled
                $order->purchaseTickets()->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $minutes . ' minutes'
                ]);
                
                // Update Payment status to cancelled if exists (sync with Order status)
                $order->payments()->where('status', '!=', 'refunded')->update([
                    'status' => 'cancelled'
                ]);
                
                // Release the tickets back to available inventory
                foreach ($order->purchaseTickets as $purchaseTicket) {
                    $ticketType = $purchaseTicket->ticketType;
                    if ($ticketType) {
                        $ticketType->update([
                            'sold_seats' => max(0, $ticketType->sold_seats - 1),
                            'available_seats' => min($ticketType->total_seats, $ticketType->available_seats + 1),
                            'status' => $ticketType->available_seats + 1 > 0 ? 'active' : $ticketType->status
                        ]);
                    }
                }
                
                $cancelledCount++;
                
                Log::info('Order cancelled due to payment timeout', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'created_at' => $order->created_at,
                    'cancelled_at' => now()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to cancel pending order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($cancelledCount > 0) {
            $this->info("Cancelled {$cancelledCount} pending orders from previous days (older than {$minutes} minutes).");
        } else {
            $this->info("No pending orders found from previous days (older than {$minutes} minutes).");
        }
        
        return Command::SUCCESS;
    }
}