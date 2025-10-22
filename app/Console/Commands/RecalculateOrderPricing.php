<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class RecalculateOrderPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:recalculate-pricing {--order-id= : Specific order ID to recalculate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate order pricing from associated tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting order pricing recalculation...');

        $query = Order::with('tickets');
        
        if ($orderId = $this->option('order-id')) {
            $query->where('id', $orderId);
        }

        $orders = $query->get();
        $updatedCount = 0;

        foreach ($orders as $order) {
            if ($order->tickets->count() > 0) {
                $oldTotal = $order->total_amount;
                $order->recalculatePricingFromTickets();
                $newTotal = $order->fresh()->total_amount;
                
                $this->line("Order #{$order->order_number}: {$oldTotal} -> {$newTotal}");
                $updatedCount++;
            } else {
                $this->warn("Order #{$order->order_number} has no tickets, skipping...");
            }
        }

        $this->info("Recalculated pricing for {$updatedCount} orders.");
        
        return Command::SUCCESS;
    }
}