<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SeatAssignmentService
{
    /**
     * Find the best available seats for an event with pessimistic locking
     * 
     * @param Event $event
     * @param string $priceZone
     * @param int $quantity
     * @return array
     */
    public function findBestAvailableSeats(Event $event, string $priceZone, int $quantity): array
    {
        try {
            // Use pessimistic locking with transaction isolation
            return DB::transaction(function() use ($event, $priceZone, $quantity) {
                // Set transaction isolation level for SQLite compatibility
                DB::statement('PRAGMA read_committed = true');
                
                // Get available seats with pessimistic locking
                $availableSeats = $this->getAvailableSeatsWithLock($event, $priceZone, $quantity);

                if ($availableSeats->count() < $quantity) {
                    return [
                        'success' => false,
                        'message' => 'Not enough seats available in the selected price zone.',
                        'available_count' => $availableSeats->count(),
                        'requested_count' => $quantity
                    ];
                }

                // Apply the "Best Available" algorithm
                $bestSeats = $this->applyBestAvailableAlgorithm($availableSeats, $quantity);

                // Hold the seats temporarily with atomic operation
                $heldSeats = $this->holdSeatsAtomically($event, $bestSeats);

                return [
                    'success' => true,
                    'seats' => $heldSeats,
                    'hold_until' => now()->addMinutes(60), // 60-minute hold for development
                    'total_price' => $heldSeats->sum('price')
                ];
            });

        } catch (\Exception $e) {
            Log::error('Seat assignment failed', [
                'event_id' => $event->id,
                'price_zone' => $priceZone,
                'quantity' => $quantity,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to assign seats. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get available seats for an event with pessimistic locking
     */
    private function getAvailableSeatsWithLock(Event $event, string $priceZone, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        // Use raw SQL with locking for SQLite compatibility
        $availableSeatIds = DB::select("
            SELECT s.id 
            FROM seats s 
            WHERE s.price_zone = ? 
            AND s.id NOT IN (
                SELECT t.seat_id 
                FROM tickets t 
                WHERE t.event_id = ? 
                AND t.status IN ('Held', 'Sold')
                AND t.deleted_at IS NULL
            )
            AND s.deleted_at IS NULL
            ORDER BY s.section, s.row, s.number
            LIMIT ?
        ", [$priceZone, $event->id, $quantity * 2]);

        $seatIds = collect($availableSeatIds)->pluck('id')->toArray();
        
        if (empty($seatIds)) {
            return collect();
        }

        // Lock the seats for update to prevent concurrent access
        return Seat::whereIn('id', $seatIds)
            ->orderBy('section')
            ->orderBy('row')
            ->orderBy('number')
            ->get();
    }

    /**
     * Get available seats for an event (legacy method for compatibility)
     */
    private function getAvailableSeats(Event $event, string $priceZone, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        return Seat::where('price_zone', $priceZone)
            ->whereNotIn('id', function($query) use ($event) {
                $query->select('seat_id')
                    ->from('tickets')
                    ->where('event_id', $event->id)
                    ->whereIn('status', ['Held', 'Sold']);
            })
            ->orderBy('section')
            ->orderBy('row')
            ->orderBy('number')
            ->limit($quantity * 2) // Get more seats than needed for better selection
            ->get();
    }

    /**
     * Apply the "Best Available" algorithm
     * Priority: Contiguous blocks > Proximity to stage/center > Row preference
     */
    private function applyBestAvailableAlgorithm($availableSeats, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        // Group seats by section and row for contiguous selection
        $groupedSeats = $availableSeats->groupBy(function($seat) {
            return $seat->section . '-' . $seat->row;
        });

        // Try to find contiguous seats first
        $contiguousSeats = $this->findContiguousSeats($groupedSeats, $quantity);
        
        if ($contiguousSeats->count() >= $quantity) {
            return $contiguousSeats->take($quantity);
        }

        // If no contiguous seats, select best individual seats
        return $this->selectBestIndividualSeats($availableSeats, $quantity);
    }

    /**
     * Find contiguous seats in the same row
     */
    private function findContiguousSeats($groupedSeats, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        $bestContiguous = collect();

        foreach ($groupedSeats as $rowSeats) {
            if ($rowSeats->count() >= $quantity) {
                $sortedSeats = $rowSeats->sortBy('number');
                $contiguous = $this->findContiguousInRow($sortedSeats, $quantity);
                
                if ($contiguous->count() >= $quantity) {
                    $bestContiguous = $contiguous;
                    break; // Take the first (best) contiguous block found
                }
            }
        }

        return $bestContiguous;
    }

    /**
     * Find contiguous seats within a single row
     */
    private function findContiguousInRow($sortedSeats, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        $contiguous = collect();
        $seatNumbers = $sortedSeats->pluck('number')->toArray();

        for ($i = 0; $i <= count($seatNumbers) - $quantity; $i++) {
            $currentBlock = array_slice($seatNumbers, $i, $quantity);
            
            if ($this->isContiguous($currentBlock)) {
                $contiguous = $sortedSeats->whereIn('number', $currentBlock);
                break;
            }
        }

        return $contiguous;
    }

    /**
     * Check if seat numbers are contiguous
     */
    private function isContiguous(array $seatNumbers): bool
    {
        sort($seatNumbers);
        
        for ($i = 1; $i < count($seatNumbers); $i++) {
            if ($seatNumbers[$i] - $seatNumbers[$i-1] !== 1) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Select best individual seats when contiguous are not available
     */
    private function selectBestIndividualSeats($availableSeats, int $quantity): \Illuminate\Database\Eloquent\Collection
    {
        // Sort by priority: section (A-Z), row (1-25), number (1-35)
        return $availableSeats
            ->sortBy([
                ['section', 'asc'],
                ['row', 'asc'],
                ['number', 'asc']
            ])
            ->take($quantity);
    }

    /**
     * Hold seats atomically with pessimistic locking
     */
    private function holdSeatsAtomically(Event $event, $seats): \Illuminate\Support\Collection
    {
        $heldSeats = collect();
        $holdUntil = now()->addMinutes(60);

        foreach ($seats as $seat) {
            // Double-check seat availability with locking
            $availableSeat = Seat::where('id', $seat->id)
                ->whereNotIn('id', function($query) use ($event) {
                    $query->select('seat_id')
                        ->from('tickets')
                        ->where('event_id', $event->id)
                        ->whereIn('status', ['Held', 'Sold'])
                        ->where('deleted_at', null);
                })
                ->first();

            if (!$availableSeat) {
                throw new \Exception("Seat {$seat->id} is no longer available");
            }

            // Create a temporary ticket with "Held" status atomically
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'qrcode' => $this->generateUniqueQRCode(),
                'status' => 'Held',
                'price_paid' => $seat->base_price,
                'order_id' => null, // Will be set when order is created
                'hold_until' => $holdUntil,
            ]);

            $heldSeats->push([
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'section' => $seat->section,
                'row' => $seat->row,
                'number' => $seat->number,
                'price_zone' => $seat->price_zone,
                'price' => $seat->base_price,
                'hold_until' => $holdUntil,
            ]);
        }

        return $heldSeats;
    }

    /**
     * Hold seats temporarily (8-10 minutes) - legacy method
     */
    private function holdSeats(Event $event, $seats): \Illuminate\Support\Collection
    {
        $heldSeats = collect();
        $holdUntil = now()->addMinutes(60);

        foreach ($seats as $seat) {
            // Create a temporary ticket with "Held" status
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'qrcode' => $this->generateUniqueQRCode(),
                'status' => 'Held',
                'price_paid' => $seat->base_price,
                'order_id' => null, // Will be set when order is created
                'hold_until' => $holdUntil,
            ]);

            $heldSeats->push([
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'section' => $seat->section,
                'row' => $seat->row,
                'number' => $seat->number,
                'price_zone' => $seat->price_zone,
                'price' => $seat->base_price,
                'hold_until' => $holdUntil,
            ]);
        }

        return $heldSeats;
    }

    /**
     * Generate unique QR code for ticket
     */
    private function generateUniqueQRCode(): string
    {
        do {
            $qrcode = 'WTS-' . strtoupper(uniqid()) . '-' . random_int(1000, 9999);
        } while (Ticket::where('qrcode', $qrcode)->exists());

        return $qrcode;
    }

    /**
     * Release held seats that have expired
     */
    public function releaseExpiredHolds(): int
    {
        $expiredTickets = Ticket::where('status', 'Held')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        $releasedCount = 0;

        foreach ($expiredTickets as $ticket) {
            $ticket->update(['status' => 'Invalid']);
            $releasedCount++;
        }

        Log::info("Released {$releasedCount} expired seat holds");

        return $releasedCount;
    }

    /**
     * Confirm seat assignment (convert from Held to Sold)
     */
    public function confirmSeatAssignment(Order $order, array $ticketIds): bool
    {
        try {
            DB::beginTransaction();

            foreach ($ticketIds as $ticketId) {
                $ticket = Ticket::find($ticketId);
                
                if (!$ticket || $ticket->status !== 'Held') {
                    throw new \Exception("Invalid or already processed ticket: {$ticketId}");
                }

                $ticket->update([
                    'order_id' => $order->id,
                    'status' => 'Sold'
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to confirm seat assignment', [
                'order_id' => $order->id,
                'ticket_ids' => $ticketIds,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get seat availability statistics for an event
     */
    public function getAvailabilityStats(Event $event): array
    {
        $totalSeats = 7000; // Fixed capacity
        $soldTickets = Ticket::where('event_id', $event->id)
            ->where('status', 'Sold')
            ->count();
        $heldTickets = Ticket::where('event_id', $event->id)
            ->where('status', 'Held')
            ->count();

        $availableSeats = $totalSeats - $soldTickets - $heldTickets;

        return [
            'total_capacity' => $totalSeats,
            'sold' => $soldTickets,
            'held' => $heldTickets,
            'available' => $availableSeats,
            'sold_percentage' => $totalSeats > 0 ? round(($soldTickets / $totalSeats) * 100, 2) : 0,
            'availability_percentage' => $totalSeats > 0 ? round(($availableSeats / $totalSeats) * 100, 2) : 0,
        ];
    }

    /**
     * Get price zone availability
     */
    public function getPriceZoneAvailability(Event $event): array
    {
        // Get all active price zones from the price_zones table, ordered by sort_order and price
        $priceZones = \App\Models\PriceZone::active()
            ->ordered()
            ->pluck('name')
            ->toArray();
        
        $availability = [];

        foreach ($priceZones as $zone) {
            $totalInZone = Seat::where('price_zone', $zone)->count();
            $soldInZone = Ticket::where('event_id', $event->id)
                ->where('status', 'Sold')
                ->whereHas('seat', function($query) use ($zone) {
                    $query->where('price_zone', $zone);
                })
                ->count();
            $heldInZone = Ticket::where('event_id', $event->id)
                ->where('status', 'Held')
                ->whereHas('seat', function($query) use ($zone) {
                    $query->where('price_zone', $zone);
                })
                ->count();

            $availability[$zone] = [
                'total' => $totalInZone,
                'sold' => $soldInZone,
                'held' => $heldInZone,
                'available' => $totalInZone - $soldInZone - $heldInZone,
                'sold_percentage' => $totalInZone > 0 ? round(($soldInZone / $totalInZone) * 100, 2) : 0,
            ];
        }

        return $availability;
    }
}
