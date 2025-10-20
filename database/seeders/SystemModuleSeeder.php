<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\PurchaseTicket;
use Illuminate\Database\Seeder;

class SystemModuleSeeder extends Seeder
{
    /**
     * Run the database seeds for System Module.
     */
    public function run(): void
    {
        $this->command->info('Seeding System Module...');

        // Clear existing audit logs to prevent duplicates
        $this->command->info('Clearing existing audit logs...');
        AuditLog::query()->delete();

        // Create audit logs for various actions
        $this->createAuditLogs();

        $this->command->info('System Module seeded successfully!');
        $this->command->info('Created: Audit logs for system activities');
    }

    /**
     * Create audit logs for system activities
     */
    private function createAuditLogs(): void
    {
        $users = User::all();
        $events = Event::all();
        $orders = Order::all();
        $purchaseTickets = PurchaseTicket::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found for audit logs');
            return;
        }

        $auditLogs = [];

        // User-related audit logs
        foreach ($users->take(5) as $user) {
            $auditLogs[] = [
                'user_id' => $user->id,
                'action' => 'CREATE',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]),
                'ip_address' => $this->getRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'description' => "User {$user->name} registered in the system",
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        // Event-related audit logs
        foreach ($events->take(3) as $event) {
            $auditLogs[] = [
                'user_id' => $users->where('role', 'administrator')->first()->id,
                'action' => 'CREATE',
                'table_name' => 'events',
                'record_id' => $event->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'name' => $event->name,
                    'status' => $event->status,
                    'venue' => $event->venue,
                ]),
                'ip_address' => $this->getRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'description' => "Event '{$event->name}' created by administrator",
                'created_at' => now()->subDays(rand(1, 20)),
                'updated_at' => now()->subDays(rand(1, 20)),
            ];

            // Event status updates
            if ($event->status === 'on_sale') {
                $auditLogs[] = [
                    'user_id' => $users->where('role', 'administrator')->first()->id,
                    'action' => 'UPDATE',
                    'table_name' => 'events',
                    'record_id' => $event->id,
                    'old_values' => json_encode(['status' => 'draft']),
                    'new_values' => json_encode(['status' => 'on_sale']),
                    'ip_address' => $this->getRandomIP(),
                    'user_agent' => $this->getRandomUserAgent(),
                    'description' => "Event '{$event->name}' status changed from draft to on sale",
                    'created_at' => now()->subDays(rand(1, 15)),
                    'updated_at' => now()->subDays(rand(1, 15)),
                ];
            }
        }

        // Order-related audit logs
        foreach ($orders->take(10) as $order) {
            $auditLogs[] = [
                'user_id' => $order->user_id,
                'action' => 'CREATE',
                'table_name' => 'orders',
                'record_id' => $order->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                ]),
                'ip_address' => $this->getRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'description' => "Order {$order->order_number} created by customer",
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ];

            // Order status updates
            if ($order->status === 'paid') {
                $auditLogs[] = [
                    'user_id' => $order->user_id,
                    'action' => 'UPDATE',
                    'table_name' => 'orders',
                    'record_id' => $order->id,
                    'old_values' => json_encode(['status' => 'pending']),
                    'new_values' => json_encode(['status' => 'paid']),
                    'ip_address' => $this->getRandomIP(),
                    'user_agent' => $this->getRandomUserAgent(),
                    'description' => "Order {$order->order_number} payment completed",
                    'created_at' => $order->created_at->addMinutes(rand(1, 30)),
                    'updated_at' => $order->created_at->addMinutes(rand(1, 30)),
                ];
            }
        }

        // Purchase ticket-related audit logs
        foreach ($purchaseTickets->take(15) as $ticket) {
            $auditLogs[] = [
                'user_id' => $ticket->order->user_id,
                'action' => 'CREATE',
                'table_name' => 'purchase',
                'record_id' => $ticket->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'qrcode' => $ticket->qrcode,
                    'status' => $ticket->status,
                    'price_paid' => $ticket->price_paid,
                ]),
                'ip_address' => $this->getRandomIP(),
                'user_agent' => $this->getRandomUserAgent(),
                'description' => "Ticket {$ticket->qrcode} purchased for event",
                'created_at' => $ticket->created_at,
                'updated_at' => $ticket->created_at,
            ];
        }

        // System maintenance logs
        $adminUser = $users->where('role', 'administrator')->first();
        if ($adminUser) {
            $auditLogs[] = [
                'user_id' => $adminUser->id,
                'action' => 'SYSTEM',
                'table_name' => 'system',
                'record_id' => 0,
                'old_values' => null,
                'new_values' => json_encode(['maintenance' => 'Database optimization completed']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Laravel Artisan',
                'description' => 'System maintenance: Database optimization completed',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ];

            $auditLogs[] = [
                'user_id' => $adminUser->id,
                'action' => 'SYSTEM',
                'table_name' => 'system',
                'record_id' => 0,
                'old_values' => null,
                'new_values' => json_encode(['backup' => 'Daily backup completed successfully']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Laravel Scheduler',
                'description' => 'System maintenance: Daily backup completed successfully',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ];
        }

        // Insert all audit logs
        foreach ($auditLogs as $log) {
            AuditLog::create($log);
        }
    }

    /**
     * Get random IP address
     */
    private function getRandomIP(): string
    {
        $ips = [
            '192.168.1.100',
            '192.168.1.101',
            '192.168.1.102',
            '10.0.0.50',
            '10.0.0.51',
            '172.16.0.10',
            '172.16.0.11',
            '203.0.113.1',
            '203.0.113.2',
            '198.51.100.1',
        ];
        return $ips[array_rand($ips)];
    }

    /**
     * Get random user agent
     */
    private function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0',
            'PostmanRuntime/7.26.8',
            'Laravel Artisan',
        ];
        return $userAgents[array_rand($userAgents)];
    }
}
