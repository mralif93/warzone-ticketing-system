<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Show cart/checkout page for zone-based ticket purchasing
     */
    public function cart(Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to proceed with your ticket purchase.')
                ->with('intended', route('public.tickets.cart', $event));
        }

        if (!$event->isOnSale()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is not currently on sale.');
        }

        // Get hold information from session (if any)
        $holdUntil = session('hold_until');

        // Check if hold has expired
        if ($holdUntil && now()->isAfter($holdUntil)) {
            session()->forget(['ticket_quantity', 'selected_zone', 'hold_until']);
            return redirect()->route('public.events.show', $event)
                ->with('error', 'Your ticket hold has expired. Please try again.');
        }

        return view('public.tickets.cart', compact('event', 'holdUntil'));
    }

    /**
     * Process ticket purchase
     */
    public function purchase(Request $request, Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to complete your ticket purchase.')
                ->with('intended', route('public.tickets.cart', $event));
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1|max:' . $event->max_tickets_per_order,
            'zone' => 'required|string|in:Warzone Exclusive,Warzone VIP,Warzone Grandstand,Warzone Premium Ringside,Level 1 Zone A/B/C/D,Level 2 Zone A/B/C/D,Standing Zone A/B',
        ]);

        if (!$event->isOnSale()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is not currently on sale.');
        }

        // Check if event has enough available seats
        if (!$event->hasAvailableSeats()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is sold out.');
        }

        // Check if requested quantity exceeds available seats
        if ($request->quantity > $event->getRemainingTicketsCount()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'Not enough seats available. Only ' . $event->getRemainingTicketsCount() . ' seats remaining.');
        }

        DB::beginTransaction();
        try {
            // Calculate base pricing
            $basePrice = $this->getZonePrice($request->zone);
            $totalPrice = $basePrice * $request->quantity;
            
            // Create single order for all tickets
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'qrcode' => Order::generateQRCode(),
                'subtotal' => $totalPrice,
                'service_fee' => $this->calculateServiceFee($totalPrice),
                'tax_amount' => $this->calculateTax($totalPrice + $this->calculateServiceFee($totalPrice)),
                'total_amount' => $totalPrice + $this->calculateServiceFee($totalPrice) + $this->calculateTax($totalPrice + $this->calculateServiceFee($totalPrice)),
                'status' => 'Paid', // Set to Paid since payment is completed
                'payment_method' => 'Credit Card',
            ]);
            
            // Create multiple tickets for this single order
            $tickets = collect();
            for ($i = 0; $i < $request->quantity; $i++) {
                $ticket = Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'zone' => $request->zone,
                    'qrcode' => Ticket::generateQRCode(),
                    'status' => 'Sold',
                    'price_paid' => $basePrice,
                ]);
                
                $tickets->push($ticket);
            }

            // Clear session
            session()->forget(['ticket_quantity', 'selected_zone', 'hold_until']);

            DB::commit();

            // Redirect to order confirmation
            return redirect()->route('public.tickets.confirmation', $order)
                ->with('success', 'Tickets purchased successfully! ' . $tickets->count() . ' tickets in 1 order.');

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

        $order->load(['tickets', 'user']);
        
        return view('public.tickets.confirmation', compact('order'));
    }

    /**
     * Show user's tickets - redirect to customer dashboard
     */
    public function myTickets()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Redirect to customer dashboard (which has tickets section)
            return redirect()->route('dashboard');
        } else {
            // Redirect to login page
            return redirect()->route('login')->with('message', 'Please login to view your tickets.');
        }
    }

    /**
     * Show individual ticket details
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $ticket->load(['event', 'order.user']);
        
        return view('public.tickets.show', compact('ticket'));
    }

    /**
     * Release held tickets (for testing/cleanup)
     */
    public function releaseHolds()
    {
        $releasedCount = Ticket::where('status', 'Held')
            ->where('created_at', '<', now()->subMinutes(10))
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Released {$releasedCount} expired ticket holds",
            'released_count' => $releasedCount
        ]);
    }

    /**
     * Get price for a specific zone
     */
    private function getZonePrice(string $zone): float
    {
        // Define zone prices based on the provided categories
        $zonePrices = [
            'Warzone Exclusive' => 500.00,
            'Warzone VIP' => 250.00,
            'Warzone Grandstand' => 199.00,
            'Warzone Premium Ringside' => 150.00,
            'Level 1 Zone A/B/C/D' => 100.00,
            'Level 2 Zone A/B/C/D' => 75.00,
            'Standing Zone A/B' => 50.00,
        ];

        return $zonePrices[$zone] ?? 50.00;
    }

    /**
     * Calculate service fee (5% of subtotal)
     */
    private function calculateServiceFee(float $subtotal): float
    {
        return round($subtotal * 0.05, 2);
    }

    /**
     * Calculate tax (6% of subtotal + service fee)
     */
    private function calculateTax(float $subtotal): float
    {
        return round($subtotal * 0.06, 2);
    }
}