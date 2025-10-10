<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\SeatAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $seatAssignmentService;

    public function __construct(SeatAssignmentService $seatAssignmentService)
    {
        $this->seatAssignmentService = $seatAssignmentService;
    }

    /**
     * Show ticket selection page for an event
     */
    public function select(Event $event)
    {
        if (!$event->isOnSale()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is not currently on sale.');
        }

        $availabilityStats = $this->seatAssignmentService->getAvailabilityStats($event);
        $priceZoneAvailability = $this->seatAssignmentService->getPriceZoneAvailability($event);

        return view('public.tickets.select', compact('event', 'availabilityStats', 'priceZoneAvailability'));
    }

    /**
     * Find and hold seats for an event
     */
    public function findSeats(Request $request, Event $event)
    {
        // Get valid price zones from database
        $validPriceZones = \App\Models\PriceZone::active()->pluck('name')->toArray();
        
        $request->validate([
            'price_zone' => 'required|in:' . implode(',', $validPriceZones),
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        if (!$event->isOnSale()) {
            return response()->json([
                'success' => false,
                'message' => 'This event is not currently on sale.'
            ], 400);
        }

        // Check if user has exceeded max tickets per order
        if ($request->quantity > $event->max_tickets_per_order) {
            return response()->json([
                'success' => false,
                'message' => "Maximum {$event->max_tickets_per_order} tickets per order allowed."
            ], 400);
        }

        $result = $this->seatAssignmentService->findBestAvailableSeats(
            $event,
            $request->price_zone,
            $request->quantity
        );

        if ($result['success']) {
            // Store the held seats in session for checkout
            session(['held_seats' => $result['seats']->toArray()]);
            session(['hold_until' => $result['hold_until']->toISOString()]);
            
            // Force save the session
            session()->save();
            
            // Debug logging
            \Log::info('Seats stored in session', [
                'session_id' => session()->getId(),
                'held_seats_count' => count($result['seats']),
                'hold_until' => $result['hold_until']->toDateTimeString()
            ]);
        }

        return response()->json($result);
    }

    /**
     * Show cart/checkout page
     */
    public function cart(Event $event)
    {
        // Force clear any problematic session data first
        $heldSeats = session('held_seats', []);
        $holdUntil = session('hold_until');
        
        // If hold_until is null, empty, or invalid, clear it
        if (!$holdUntil || empty($holdUntil) || $holdUntil === 'null') {
            session()->forget(['held_seats', 'hold_until']);
            $heldSeats = [];
            $holdUntil = null;
        }

        // Debug logging
        \Log::info('Cart method called', [
            'session_id' => session()->getId(),
            'held_seats_count' => count($heldSeats),
            'hold_until' => $holdUntil,
            'current_time' => now()->toDateTimeString()
        ]);

        // Always redirect to select-seats if no seats are held
        if (empty($heldSeats)) {
            return redirect()->route('public.tickets.select', $event)
                ->with('info', 'Please select your seats first.');
        }

        // Only check expiration if we have a valid hold_until timestamp
        if ($holdUntil && !empty($holdUntil) && $holdUntil !== 'null') {
            try {
                $holdTime = \Carbon\Carbon::parse($holdUntil);
                if (now()->isAfter($holdTime)) {
                    session()->forget(['held_seats', 'hold_until']);
                    return redirect()->route('public.tickets.select', $event)
                        ->with('info', 'Your seat selection has expired. Please select seats again.');
                }
            } catch (\Exception $e) {
                // If parsing fails, clear the session and redirect
                session()->forget(['held_seats', 'hold_until']);
                return redirect()->route('public.tickets.select', $event)
                    ->with('info', 'Please select your seats first.');
            }
        }

        // Extract zone and quantity information from held seats
        $selectedZone = null;
        $quantity = count($heldSeats);
        
        if (!empty($heldSeats)) {
            // Get the zone from the first seat (all seats should be in the same zone)
            $selectedZone = $heldSeats[0]['price_zone'] ?? 'General';
        }

        $totalPrice = collect($heldSeats)->sum('price');
        $serviceFee = $this->calculateServiceFee($totalPrice);
        $taxAmount = $this->calculateTax($totalPrice + $serviceFee);
        $grandTotal = $totalPrice + $serviceFee + $taxAmount;

        return view('public.tickets.cart', compact(
            'event',
            'heldSeats',
            'holdUntil',
            'selectedZone',
            'quantity',
            'totalPrice',
            'serviceFee',
            'taxAmount',
            'grandTotal'
        ));
    }

    /**
     * Process ticket purchase
     */
    public function purchase(Request $request, Event $event)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $heldSeats = session('held_seats', []);
        $holdUntil = session('hold_until');

        if (empty($heldSeats)) {
            return redirect()->route('public.tickets.select', $event)
                ->with('error', 'No seats selected. Please select seats first.');
        }

        // Check if hold has expired
        if ($holdUntil && now()->isAfter($holdUntil)) {
            session()->forget(['held_seats', 'hold_until']);
            return redirect()->route('public.tickets.select', $event)
                ->with('error', 'Your seat hold has expired. Please select seats again.');
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => collect($heldSeats)->sum('price'),
                'service_fee' => $this->calculateServiceFee(collect($heldSeats)->sum('price')),
                'tax_amount' => $this->calculateTax(collect($heldSeats)->sum('price') + $this->calculateServiceFee(collect($heldSeats)->sum('price'))),
                'total_amount' => $this->calculateGrandTotal($heldSeats),
                'status' => 'Pending',
                'payment_method' => 'Credit Card', // Will be updated when payment is processed
                'held_until' => $holdUntil,
            ]);

            // Confirm seat assignment
            $ticketIds = collect($heldSeats)->pluck('ticket_id')->toArray();
            $confirmed = $this->seatAssignmentService->confirmSeatAssignment($order, $ticketIds);

            if (!$confirmed) {
                throw new \Exception('Failed to confirm seat assignment');
            }

            // Clear session
            session()->forget(['held_seats', 'hold_until']);

            DB::commit();

            return redirect()->route('tickets.confirmation', $order)
                ->with('success', 'Tickets purchased successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase failed', [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to process purchase. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show purchase confirmation
     */
    public function confirmation(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['tickets.seat', 'user']);
        
        return view('public.tickets.confirmation', compact('order'));
    }

    /**
     * Show user's tickets
     */
    public function myTickets()
    {
        $tickets = Ticket::whereHas('order', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['event', 'seat', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.tickets.my-tickets', compact('tickets'));
    }

    /**
     * Show individual ticket details
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $ticket->load(['event', 'seat', 'order.user']);
        
        return view('public.tickets.show', compact('ticket'));
    }

    /**
     * Release held seats (for testing/cleanup)
     */
    public function releaseHolds()
    {
        $releasedCount = $this->seatAssignmentService->releaseExpiredHolds();
        
        return response()->json([
            'success' => true,
            'message' => "Released {$releasedCount} expired seat holds",
            'released_count' => $releasedCount
        ]);
    }

    /**
     * Calculate service fee (5% of subtotal)
     */
    private function calculateServiceFee(float $subtotal): float
    {
        return round($subtotal * 0.05, 2);
    }

    /**
     * Calculate tax (8% of subtotal + service fee)
     */
    private function calculateTax(float $subtotal): float
    {
        return round($subtotal * 0.08, 2);
    }

    /**
     * Calculate grand total
     */
    private function calculateGrandTotal(array $heldSeats): float
    {
        $subtotal = collect($heldSeats)->sum('price');
        $serviceFee = $this->calculateServiceFee($subtotal);
        $taxAmount = $this->calculateTax($subtotal + $serviceFee);
        
        return $subtotal + $serviceFee + $taxAmount;
    }
}