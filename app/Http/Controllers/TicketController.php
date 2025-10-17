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
     * Show cart/checkout page for ticket purchasing
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

        // Load tickets for this event
        $event->load(['tickets' => function($query) {
            $query->where('status', 'Active');
        }]);

        // Check if there are any available tickets
        if ($event->tickets->where('available_seats', '>', 0)->count() === 0) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is sold out.');
        }

        // Get hold information from session (if any)
        $holdUntil = session('hold_until');

        // Check if hold has expired
        if ($holdUntil && now()->isAfter($holdUntil)) {
            session()->forget(['ticket_quantity', 'selected_ticket_type', 'hold_until']);
            return redirect()->route('public.events.show', $event)
                ->with('error', 'Your ticket hold has expired. Please try again.');
        }

        return view('public.tickets.cart', compact('event', 'holdUntil'));
    }

    /**
     * Process ticket purchase using admin order creation logic
     */
    public function purchase(Request $request, Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to complete your ticket purchase.')
                ->with('intended', route('public.tickets.cart', $event));
        }

        // Load tickets for this event
        $event->load(['tickets' => function($query) {
            $query->where('status', 'Active');
        }]);

        // Get available ticket IDs for validation
        $availableTicketIds = $event->tickets->where('available_seats', '>', 0)->pluck('id')->toArray();

        // Validate based on purchase type
        $purchaseType = $request->purchase_type ?? 'single_day';
        
        if ($purchaseType === 'single_day') {
            $request->validate([
                'purchase_type' => 'required|in:single_day',
                'ticket_type_id' => 'required|integer|in:' . implode(',', $availableTicketIds),
                'quantity' => 'required|integer|min:1|max:10',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_method' => 'required|string|in:Bank Transfer,Online Banking,QR Code / E-Wallet,Debit / Credit Card,Others',
            ]);
        } else {
            $request->validate([
                'purchase_type' => 'required|in:multi_day',
                'day1_ticket_type' => 'nullable|integer|in:' . implode(',', $availableTicketIds),
                'day1_quantity' => 'nullable|integer|min:0|max:10',
                'day2_ticket_type' => 'nullable|integer|in:' . implode(',', $availableTicketIds),
                'day2_quantity' => 'nullable|integer|min:0|max:10',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_method' => 'required|string|in:Bank Transfer,Online Banking,QR Code / E-Wallet,Debit / Credit Card,Others',
            ]);

            // At least one day must have tickets
            if (($request->day1_quantity ?? 0) === 0 && ($request->day2_quantity ?? 0) === 0) {
                return redirect()->back()
                    ->with('error', 'Please select at least one day with tickets.')
                    ->withInput();
            }
        }

        if (!$event->isOnSale()) {
            return redirect()->route('public.events.show', $event)
                ->with('error', 'This event is not currently on sale.');
        }

        DB::beginTransaction();
        try {
            $tickets = [];
            $totalQuantity = 0;
            $subtotal = 0;
            $discountAmount = 0;
            
            // Handle different purchase types using admin logic
            if ($purchaseType === 'multi_day') {
                // Multi-day purchase (Day 1 & Day 2) with individual quantities
                $day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
                $day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
                $day1Quantity = $request->day1_quantity ?? 1;
                $day2Quantity = $request->day2_quantity ?? 1;
                
                // Verify ticket types belong to selected event
                if ($day1Ticket->event_id != $event->id || $day2Ticket->event_id != $event->id) {
                    return redirect()->back()
                        ->with('error', 'Selected ticket types do not belong to the selected event.')
                        ->withInput();
                }
                
                // Check availability
                if ($day1Ticket->available_seats < $day1Quantity || $day2Ticket->available_seats < $day2Quantity) {
                    return redirect()->back()
                        ->with('error', 'Insufficient tickets available for one or both days.')
                        ->withInput();
                }
                
                // Calculate pricing
                $day1Price = $day1Ticket->price;
                $day2Price = $day2Ticket->price;
                $day1Subtotal = $day1Price * $day1Quantity;
                $day2Subtotal = $day2Price * $day2Quantity;
                $subtotal = $day1Subtotal + $day2Subtotal;
                
                // Apply combo discount if enabled
                $isCombo = $event->isTwoDayEvent() && $event->combo_discount_enabled;
                if ($isCombo) {
                    $discountAmount = $event->calculateComboDiscount($subtotal);
                    $subtotal = $subtotal - $discountAmount;
                }
                
                $tickets = [
                    ['ticket' => $day1Ticket, 'price' => $day1Price, 'quantity' => $day1Quantity, 'day' => 1],
                    ['ticket' => $day2Ticket, 'price' => $day2Price, 'quantity' => $day2Quantity, 'day' => 2]
                ];
                $totalQuantity = $day1Quantity + $day2Quantity;
                
            } else {
                // Single day purchase
                $ticketType = \App\Models\Ticket::findOrFail($request->ticket_type_id);
                
                // Verify ticket type belongs to selected event
                if ($ticketType->event_id != $event->id) {
                    return redirect()->back()
                        ->with('error', 'Selected ticket type does not belong to the selected event.')
                        ->withInput();
                }
                
                // Check availability
                $quantity = $request->quantity;
                if ($ticketType->available_seats < $quantity) {
                    return redirect()->back()
                        ->with('error', 'Insufficient tickets available. Only ' . $ticketType->available_seats . ' tickets remaining.')
                        ->withInput();
                }
                
                // Calculate pricing
                $basePrice = $ticketType->price;
                $subtotal = $basePrice * $quantity;
                
                $tickets = [
                    ['ticket' => $ticketType, 'price' => $basePrice, 'quantity' => $quantity]
                ];
                $totalQuantity = $quantity;
            }
            
            // Calculate final pricing using admin logic
            $serviceFee = $this->calculateServiceFee($subtotal);
            $taxAmount = $this->calculateTax($subtotal + $serviceFee);
            $totalAmount = $subtotal + $serviceFee + $taxAmount;
            
            // Create order using admin order creation logic
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'qrcode' => Order::generateQRCode(),
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'Paid',
                'payment_method' => $request->payment_method,
                'notes' => $purchaseType === 'multi_day' ? 'Multi-day purchase with combo discount' : 'Single day purchase',
            ]);

            // Create purchase tickets using admin logic
            $comboGroupId = ($purchaseType === 'multi_day') ? 'COMBO_' . $order->id . '_' . time() : null;
            
            foreach ($tickets as $ticketData) {
                $ticket = $ticketData['ticket'];
                $price = $ticketData['price'];
                $quantity = $ticketData['quantity'] ?? 1;
                $day = $ticketData['day'] ?? null;
                
                for ($i = 0; $i < $quantity; $i++) {
                    \App\Models\PurchaseTicket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'ticket_type_id' => $ticket->id,
                        'zone' => $ticket->name,
                        'event_day' => $day ? $event->getEventDays()[$day-1]['date'] : $event->getEventDays()[0]['date'],
                        'event_day_name' => $day ? $event->getEventDays()[$day-1]['day_name'] : $event->getEventDays()[0]['day_name'],
                        'is_combo_purchase' => $purchaseType === 'multi_day',
                        'combo_group_id' => $comboGroupId,
                        'original_price' => $price,
                        'discount_amount' => $discountAmount > 0 ? $discountAmount / $totalQuantity : 0,
                        'qrcode' => \App\Models\PurchaseTicket::generateQRCode(),
                        'status' => 'Sold',
                        'price_paid' => $discountAmount > 0 ? ($subtotal / $totalQuantity) : $price,
                    ]);
                }
                
                // Update ticket type availability
                $ticket->update([
                    'sold_seats' => $ticket->sold_seats + $quantity,
                    'available_seats' => $ticket->available_seats - $quantity,
                    'status' => $ticket->available_seats - $quantity <= 0 ? 'Sold Out' : 'Active',
                ]);
            }

            // Create audit log
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE',
                'table_name' => 'orders',
                'record_id' => $order->id,
                'old_values' => null,
                'new_values' => $order->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Clear session
            session()->forget(['ticket_quantity', 'selected_ticket_type', 'hold_until']);

            DB::commit();

            // Redirect to order confirmation
            $purchaseTypeText = $purchaseType === 'multi_day' ? 'tickets (Day 1 & Day 2)' : 'tickets';
            return redirect()->route('public.tickets.confirmation', $order)
                ->with('success', 'Tickets purchased successfully! ' . $totalQuantity . ' ' . $purchaseTypeText . ' in 1 order.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase failed', [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Failed to process purchase: ' . $e->getMessage()])
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