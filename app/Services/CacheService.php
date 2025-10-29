<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60; // 1 hour
    const SHORT_CACHE_DURATION = 5; // 5 minutes for frequently changing data

    /**
     * Get event availability statistics with caching
     */
    public function getEventAvailabilityStats(int $eventId): array
    {
        $cacheKey = "event_availability_stats_{$eventId}";
        
        return Cache::remember($cacheKey, self::SHORT_CACHE_DURATION, function() use ($eventId) {
            $event = Event::find($eventId);
            if (!$event) {
                return [];
            }

            // Get ticket counts by status for zone-based system
            $ticketCounts = DB::select("
                SELECT 
                    COUNT(CASE WHEN status = 'sold' THEN 1 END) as sold_count,
                    COUNT(CASE WHEN status = 'held' THEN 1 END) as held_count,
                    COUNT(CASE WHEN status = 'scanned' THEN 1 END) as scanned_count
                FROM tickets 
                WHERE event_id = ? AND deleted_at IS NULL
            ", [$eventId]);

            $counts = $ticketCounts[0] ?? (object)['sold_count' => 0, 'held_count' => 0, 'scanned_count' => 0];
            $totalCapacity = $event->getTotalCapacity();
            $available = $totalCapacity - $counts->sold_count - $counts->held_count;

            return [
                'event_id' => $eventId,
                'event_name' => $event->name,
                'total_seats' => $totalCapacity,
                'sold' => $counts->sold_count,
                'held' => $counts->held_count,
                'scanned' => $counts->scanned_count,
                'available' => max(0, $available),
                'sold_percentage' => $totalCapacity > 0 ? round(($counts->sold_count / $totalCapacity) * 100, 2) : 0,
                'availability_percentage' => $totalCapacity > 0 ? round(($available / $totalCapacity) * 100, 2) : 0,
                'cached_at' => now()->toISOString(),
            ];
        });
    }

    /**
     * Get zone availability with caching
     */
    public function getZoneAvailability(int $eventId): array
    {
        $cacheKey = "zone_availability_{$eventId}";
        
        return Cache::remember($cacheKey, self::SHORT_CACHE_DURATION, function() use ($eventId) {
            $zones = [
                'Warzone Exclusive' => 100,
                'Warzone VIP' => 28,
                'Warzone Grandstand' => 60,
                'Warzone Premium Ringside' => 1716,
                'Level 1 Zone A/B/C/D' => 1946,
                'Level 2 Zone A/B/C/D' => 1682,
                'Standing Zone A/B' => 300,
            ];
            
            $availability = [];

            foreach ($zones as $zone => $totalSeats) {
                $zoneStats = DB::select("
                    SELECT 
                        COUNT(CASE WHEN status = 'sold' THEN 1 END) as sold_count,
                        COUNT(CASE WHEN status = 'held' THEN 1 END) as held_count,
                        COUNT(CASE WHEN status = 'scanned' THEN 1 END) as scanned_count
                    FROM tickets 
                    WHERE event_id = ? AND zone = ? AND deleted_at IS NULL
                ", [$eventId, $zone]);

                $stats = $zoneStats[0] ?? (object)['sold_count' => 0, 'held_count' => 0, 'scanned_count' => 0];
                $available = $totalSeats - $stats->sold_count - $stats->held_count;

                $availability[$zone] = [
                    'total' => $totalSeats,
                    'sold' => $stats->sold_count,
                    'held' => $stats->held_count,
                    'scanned' => $stats->scanned_count,
                    'available' => max(0, $available),
                    'sold_percentage' => $totalSeats > 0 ? round(($stats->sold_count / $totalSeats) * 100, 2) : 0,
                ];
            }

            return $availability;
        });
    }

    /**
     * Get active events with caching
     */
    public function getActiveEvents(): array
    {
        $cacheKey = 'active_events';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() {
            return Event::where('status', 'on_sale')
                ->where('date_time', '>', now())
                ->orderBy('date_time')
                ->get()
                ->map(function($event) {
                    return [
                        'id' => $event->id,
                        'name' => $event->name,
                        'date_time' => $event->date_time,
                        'venue' => $event->venue,
                        'status' => $event->status,
                        'max_tickets_per_order' => $event->max_tickets_per_order,
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Get seat pricing by zone with caching
     */
    public function getSeatPricingByZone(): array
    {
        $cacheKey = 'seat_pricing_by_zone';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() {
            $pricing = DB::select("
                SELECT 
                    price_zone,
                    MIN(base_price) as min_price,
                    MAX(base_price) as max_price,
                    AVG(base_price) as avg_price,
                    COUNT(*) as seat_count
                FROM seats 
                WHERE deleted_at IS NULL
                GROUP BY price_zone
                ORDER BY avg_price DESC
            ");

            return collect($pricing)->mapWithKeys(function($zone) {
                return [$zone->price_zone => [
                    'min_price' => $zone->min_price,
                    'max_price' => $zone->max_price,
                    'avg_price' => round($zone->avg_price, 2),
                    'seat_count' => $zone->seat_count,
                ]];
            })->toArray();
        });
    }

    /**
     * Get system statistics with caching
     */
    public function getSystemStats(): array
    {
        $cacheKey = 'system_stats';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() {
            // Use Eloquent models for better compatibility
            $totalUsers = \App\Models\User::count();
            $totalEvents = \App\Models\Event::count();
            $activeEvents = \App\Models\Event::where('status', 'on_sale')->count();
            $soldTickets = \App\Models\PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])->count();
            $heldTickets = \App\Models\PurchaseTicket::where('status', 'held')->count();
            $completedOrders = \App\Models\Order::where('status', 'paid')->count();
            $totalRevenue = \App\Models\Order::where('status', 'paid')->sum('total_amount');

            return [
                'total_users' => $totalUsers,
                'total_events' => $totalEvents,
                'active_events' => $activeEvents,
                'sold_tickets' => $soldTickets,
                'held_tickets' => $heldTickets,
                'completed_orders' => $completedOrders,
                'total_revenue' => round($totalRevenue, 2),
                'cached_at' => now()->toISOString(),
            ];
        });
    }

    /**
     * Get user role distribution with caching
     */
    public function getUserRoleDistribution(): array
    {
        $cacheKey = 'user_role_distribution';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() {
            $roles = \App\Models\User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->orderBy('count', 'desc')
                ->get();

            return $roles->mapWithKeys(function($role) {
                return [$role->role => $role->count];
            })->toArray();
        });
    }

    /**
     * Clear all caches
     */
    public function clearAllCaches(): void
    {
        $patterns = [
            'event_availability_stats_*',
            'price_zone_availability_*',
            'active_events',
            'seat_pricing_by_zone',
            'system_stats',
            'user_role_distribution',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }

        // Clear all cache if using Redis or similar
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            Cache::flush();
        }
    }

    /**
     * Clear event-specific caches
     */
    public function clearEventCaches(int $eventId): void
    {
        Cache::forget("event_availability_stats_{$eventId}");
        Cache::forget("price_zone_availability_{$eventId}");
        Cache::forget('active_events');
        Cache::forget('system_stats');
    }

    /**
     * Clear user-related caches
     */
    public function clearUserCaches(): void
    {
        Cache::forget('system_stats');
        Cache::forget('user_role_distribution');
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        $cacheKeys = [
            'event_availability_stats_*',
            'price_zone_availability_*',
            'active_events',
            'seat_pricing_by_zone',
            'system_stats',
            'user_role_distribution',
        ];

        $stats = [];
        foreach ($cacheKeys as $key) {
            $stats[$key] = Cache::has($key) ? 'cached' : 'not_cached';
        }

        return [
            'cache_keys' => $stats,
            'cache_driver' => config('cache.default'),
            'cache_prefix' => config('cache.prefix'),
        ];
    }
}
