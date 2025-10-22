<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Setting;
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
            $query->where('status', 'active');
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

        // Get service fee and tax settings
        $serviceFeePercentage = Setting::get('service_fee_percentage', 5.0);
        $taxPercentage = Setting::get('tax_percentage', 6.0);

        return view('public.tickets.cart', compact('event', 'holdUntil', 'serviceFeePercentage', 'taxPercentage'));
    }

    /**
     * Show checkout page for ticket purchasing
     */
    public function checkout(Request $request, Event $event)
    {
        try {
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
            $query->where('status', 'active');
        }]);

        // Get available ticket IDs for validation
        $availableTicketIds = $event->tickets->where('available_seats', '>', 0)->pluck('id')->toArray();

        // Validate based on purchase type
        $purchaseType = $request->purchase_type ?? 'single_day';
        
        if ($purchaseType === 'single_day') {
            $validationRules = [
                'purchase_type' => 'required|in:single_day',
                'ticket_type_id' => 'required|integer|in:' . implode(',', $availableTicketIds),
                'quantity' => 'required|integer|min:1|max:10',
            ];
            
            // Add day selection validation for multi-day events
            if ($event->isMultiDay()) {
                $validationRules['single_day_selection'] = 'required|in:day1,day2';
            }
            
            $request->validate($validationRules);
        } else {
            $validationRules = [
                'purchase_type' => 'required|in:multi_day',
            ];
            
            // Add flexible day validation from checkboxes
            $day1Enabled = $request->boolean('multi_day1_enabled');
            $day2Enabled = $request->boolean('multi_day2_enabled');
            
            // Ensure at least one day is selected
            if (!$day1Enabled && !$day2Enabled) {
                return redirect()->back()
                    ->with('error', 'Please select at least one day to attend.')
                    ->withInput();
            }
            
            // Add validation rules for enabled days
            if ($day1Enabled) {
                $validationRules['day1_ticket_type'] = 'required|integer|in:' . implode(',', $availableTicketIds);
                $validationRules['day1_quantity'] = 'required|integer|min:1|max:10';
            }
            
            if ($day2Enabled) {
                $validationRules['day2_ticket_type'] = 'required|integer|in:' . implode(',', $availableTicketIds);
                $validationRules['day2_quantity'] = 'required|integer|min:1|max:10';
            }
            
            $request->validate($validationRules);
        }

        // Calculate pricing
        $tickets = [];
        $totalQuantity = 0;
        $subtotal = 0;
        $discountAmount = 0;
        
        if ($purchaseType === 'single_day') {
            $ticketType = \App\Models\Ticket::findOrFail($request->ticket_type_id);
            $quantity = $request->quantity;
            $basePrice = $ticketType->price;
            $subtotal = $basePrice * $quantity;
            
            // Determine which day for multi-day events
            $selectedDay = null;
            if ($event->isMultiDay() && $request->has('single_day_selection')) {
                $selectedDay = $request->single_day_selection === 'day1' ? 1 : 2;
            }
            
            $tickets = [
                ['ticket' => $ticketType, 'price' => $basePrice, 'quantity' => $quantity, 'day' => $selectedDay]
            ];
            $totalQuantity = $quantity;
        } else {
            // Multi-day purchase with flexible day selection
            $day1Enabled = $request->boolean('multi_day1_enabled');
            $day2Enabled = $request->boolean('multi_day2_enabled');
            
            // Process Day 1 if enabled
            if ($day1Enabled) {
                $day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
                $day1Quantity = $request->day1_quantity ?? 1;
                $tickets[] = ['ticket' => $day1Ticket, 'price' => $day1Ticket->price, 'quantity' => $day1Quantity, 'day' => 1];
                $totalQuantity += $day1Quantity;
            }
            
            // Process Day 2 if enabled
            if ($day2Enabled) {
                $day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
                $day2Quantity = $request->day2_quantity ?? 1;
                $tickets[] = ['ticket' => $day2Ticket, 'price' => $day2Ticket->price, 'quantity' => $day2Quantity, 'day' => 2];
                $totalQuantity += $day2Quantity;
            }
            
            // Calculate subtotal
            foreach ($tickets as $ticketData) {
                $subtotal += $ticketData['price'] * $ticketData['quantity'];
            }
            
            // Apply combo discount if both days are enabled and have tickets
            if ($day1Enabled && $day2Enabled && $event->combo_discount_enabled) {
                $discountAmount = $event->calculateComboDiscount($subtotal);
                $subtotal = $subtotal - $discountAmount;
            }
        }
        
        // Calculate final pricing
        $serviceFee = $this->calculateServiceFee($subtotal);
        $taxAmount = $this->calculateTax($subtotal + $serviceFee);
        $totalAmount = $subtotal + $serviceFee + $taxAmount;
        
        // Get service fee and tax settings
        $serviceFeePercentage = Setting::get('service_fee_percentage', 5.0);
        $taxPercentage = Setting::get('tax_percentage', 6.0);

        return view('public.tickets.checkout', compact('event', 'tickets', 'purchaseType', 'subtotal', 'discountAmount', 'serviceFee', 'taxAmount', 'totalAmount', 'totalQuantity', 'serviceFeePercentage', 'taxPercentage'));
        
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Checkout page error', [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Redirect to cart page with error message
            return redirect()->route('public.tickets.cart', $event)
                ->with('error', 'Unable to load checkout page. Please try again or contact support if the problem persists.');
        }
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
            $query->where('status', 'active');
        }]);

        // Get available ticket IDs for validation
        $availableTicketIds = $event->tickets->where('available_seats', '>', 0)->pluck('id')->toArray();

        // Validate based on purchase type
        $purchaseType = $request->purchase_type ?? 'single_day';
        
        if ($purchaseType === 'single_day') {
            $validationRules = [
                'purchase_type' => 'required|in:single_day',
                'ticket_type_id' => 'required|integer|in:' . implode(',', $availableTicketIds),
                'quantity' => 'required|integer|min:1|max:10',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_method' => 'required|string|in:credit_card,online_banking,e_wallet,bank_transfer',
            ];
            
            // Add day selection validation for multi-day events
            if ($event->isMultiDay()) {
                $validationRules['single_day_selection'] = 'required|in:day1,day2';
            }
            
            $request->validate($validationRules);
        } else {
            $validationRules = [
                'purchase_type' => 'required|in:multi_day',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_method' => 'required|string|in:credit_card,online_banking,e_wallet,bank_transfer',
            ];
            
            // Add flexible day validation from checkboxes
            $day1Enabled = $request->boolean('multi_day1_enabled');
            $day2Enabled = $request->boolean('multi_day2_enabled');
            
            // Ensure at least one day is selected
            if (!$day1Enabled && !$day2Enabled) {
                return redirect()->back()
                    ->with('error', 'Please select at least one day to attend.')
                    ->withInput();
            }
            
            // Add validation rules for enabled days
            if ($day1Enabled) {
                $validationRules['day1_ticket_type'] = 'required|integer|in:' . implode(',', $availableTicketIds);
                $validationRules['day1_quantity'] = 'required|integer|min:1|max:10';
            }
            
            if ($day2Enabled) {
                $validationRules['day2_ticket_type'] = 'required|integer|in:' . implode(',', $availableTicketIds);
                $validationRules['day2_quantity'] = 'required|integer|min:1|max:10';
            }
            
            $request->validate($validationRules);
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
                // Multi-day purchase with flexible day selection
                $day1Enabled = $request->boolean('multi_day1_enabled');
                $day2Enabled = $request->boolean('multi_day2_enabled');
                $tickets = [];
                $totalQuantity = 0;
                
                // Process Day 1 if enabled
                if ($day1Enabled) {
                    $day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
                    $day1Quantity = $request->day1_quantity ?? 1;
                    
                    // Verify ticket type belongs to selected event
                    if ($day1Ticket->event_id != $event->id) {
                        return redirect()->back()
                            ->with('error', 'Selected Day 1 ticket type does not belong to the selected event.')
                            ->withInput();
                    }
                    
                    // Check availability
                    if ($day1Ticket->available_seats < $day1Quantity) {
                        return redirect()->back()
                            ->with('error', 'Insufficient Day 1 tickets available. Only ' . $day1Ticket->available_seats . ' tickets remaining.')
                            ->withInput();
                    }
                    
                    $tickets[] = ['ticket' => $day1Ticket, 'price' => $day1Ticket->price, 'quantity' => $day1Quantity, 'day' => 1];
                    $totalQuantity += $day1Quantity;
                }
                
                // Process Day 2 if enabled
                if ($day2Enabled) {
                    $day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
                    $day2Quantity = $request->day2_quantity ?? 1;
                    
                    // Verify ticket type belongs to selected event
                    if ($day2Ticket->event_id != $event->id) {
                        return redirect()->back()
                            ->with('error', 'Selected Day 2 ticket type does not belong to the selected event.')
                            ->withInput();
                    }
                    
                    // Check availability
                    if ($day2Ticket->available_seats < $day2Quantity) {
                        return redirect()->back()
                            ->with('error', 'Insufficient Day 2 tickets available. Only ' . $day2Ticket->available_seats . ' tickets remaining.')
                            ->withInput();
                    }
                    
                    $tickets[] = ['ticket' => $day2Ticket, 'price' => $day2Ticket->price, 'quantity' => $day2Quantity, 'day' => 2];
                    $totalQuantity += $day2Quantity;
                }
                
                // Calculate subtotal
                $subtotal = 0;
                foreach ($tickets as $ticketData) {
                    $subtotal += $ticketData['price'] * $ticketData['quantity'];
                }
                
                // Apply combo discount if both days are enabled and have tickets
                if ($day1Enabled && $day2Enabled && $event->combo_discount_enabled) {
                    $discountAmount = $event->calculateComboDiscount($subtotal);
                    $subtotal = $subtotal - $discountAmount;
                }
                
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
                
                // Determine which day for multi-day events
                $selectedDay = null;
                if ($event->isMultiDay() && $request->has('single_day_selection')) {
                    $selectedDay = $request->single_day_selection === 'day1' ? 1 : 2;
                }
                
                $tickets = [
                    ['ticket' => $ticketType, 'price' => $basePrice, 'quantity' => $quantity, 'day' => $selectedDay]
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
                'event_id' => $event->id,
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'qrcode' => Order::generateQRCode(),
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $purchaseType === 'multi_day' ? 'Multi-day purchase with flexible day selection' : 'Single day purchase',
            ]);

            // Create purchase tickets using admin logic
            $comboGroupId = ($purchaseType === 'multi_day') ? 'COMBO_' . $order->id . '_' . time() : null;
            
            foreach ($tickets as $ticketData) {
                $ticket = $ticketData['ticket'];
                $price = $ticketData['price'];
                $quantity = $ticketData['quantity'] ?? 1;
                $day = $ticketData['day'] ?? null;
                
                // Determine event day information
                $eventDay = null;
                $eventDayName = null;
                
                if ($day) {
                    // Multi-day event with specific day
                    $eventDays = $event->getEventDays();
                    $eventDay = $eventDays[$day-1]['date'];
                    $eventDayName = $eventDays[$day-1]['day_name'];
                } else {
                    // Single day event or no specific day
                    $eventDays = $event->getEventDays();
                    $eventDay = $eventDays[0]['date'];
                    $eventDayName = $eventDays[0]['day_name'];
                }
                
                for ($i = 0; $i < $quantity; $i++) {
                    \App\Models\PurchaseTicket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'ticket_type_id' => $ticket->id,
                        'zone' => $ticket->name,
                        'event_day' => $eventDay,
                        'event_day_name' => $eventDayName,
                        'is_combo_purchase' => $purchaseType === 'multi_day' && count($tickets) > 1,
                        'combo_group_id' => $comboGroupId,
                        'original_price' => $price,
                        'discount_amount' => $discountAmount > 0 ? $discountAmount / $totalQuantity : 0,
                        'qrcode' => \App\Models\PurchaseTicket::generateQRCode(),
                        'status' => 'pending',
                        'price_paid' => $discountAmount > 0 ? ($subtotal / $totalQuantity) : $price,
                    ]);
                }
                
                // Update ticket type availability
                $ticket->update([
                    'sold_seats' => $ticket->sold_seats + $quantity,
                    'available_seats' => $ticket->available_seats - $quantity,
                    'status' => $ticket->available_seats - $quantity <= 0 ? 'sold_out' : 'active',
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

            // Redirect to success page
            return redirect()->route('public.tickets.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase failed', [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Redirect to failure page with error details
            return redirect()->route('public.tickets.failure', $event)
                ->with('error_message', 'Failed to process purchase: ' . $e->getMessage())
                ->with('error_code', 'PURCHASE_ERROR')
                ->with('transaction_id', 'N/A');
        }
    }

    /**
     * Show purchase success page
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['tickets.ticketType', 'user', 'event']);
        $event = $order->event;
        
        // Calculate totals for display
        $totalQuantity = $order->tickets->count(); // Count of purchase tickets
        
        // Use current system settings for percentage display
        // This ensures the success page shows the current settings, not historical ones
        $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0);
        $taxPercentage = Setting::get('tax_percentage', 0.0);
        
        // Round to 1 decimal place for display
        $serviceFeePercentage = round($serviceFeePercentage, 1);
        $taxPercentage = round($taxPercentage, 1);
        
        // Check if this was a combo purchase and calculate discount
        $comboDiscountAmount = 0;
        $originalSubtotal = $order->subtotal;
        
        // Determine if this was a combo purchase by checking if tickets span multiple days
        $dayNumbers = [];
        foreach ($order->tickets as $purchaseTicket) {
            if ($purchaseTicket->event_day_name) {
                preg_match('/Day (\d+)/', $purchaseTicket->event_day_name, $matches);
                if (isset($matches[1])) {
                    $dayNumbers[] = (int)$matches[1];
                }
            }
        }
        $uniqueDays = array_unique($dayNumbers);
        
        // If we have tickets for multiple days and combo discount is enabled, calculate the discount
        if (count($uniqueDays) > 1 && $event->combo_discount_enabled) {
            // Calculate original subtotal before discount using original_price
            $originalSubtotal = $order->tickets->sum('original_price');
            
            // Calculate what the discount would have been
            $comboDiscountAmount = $event->calculateComboDiscount($originalSubtotal);
            
            // Check if discount was actually applied during order creation
            $expectedDiscountedSubtotal = $originalSubtotal - $comboDiscountAmount;
            $actualSubtotal = $order->subtotal;
            
            // If the discount was applied, use the actual values
            if (abs($expectedDiscountedSubtotal - $actualSubtotal) < 0.01) {
                // Discount was applied during order creation - use actual values
                $originalSubtotal = $originalSubtotal;
                $comboDiscountAmount = $comboDiscountAmount;
            } else {
                // Discount was not applied during order creation, but this is still a combo purchase
                // Show what the discount would have been for transparency
                $originalSubtotal = $originalSubtotal;
                $comboDiscountAmount = $comboDiscountAmount;
            }
        }
        
        // Group tickets by type and day for display
        $ticketGroups = [];
        foreach ($order->tickets as $purchaseTicket) {
            $key = $purchaseTicket->ticket_type_id . '_' . ($purchaseTicket->event_day_name ?? 'default');
            if (!isset($ticketGroups[$key])) {
                // Extract day number from event_day_name (e.g., "Day 1" -> 1)
                $dayNumber = null;
                if ($purchaseTicket->event_day_name) {
                    preg_match('/Day (\d+)/', $purchaseTicket->event_day_name, $matches);
                    $dayNumber = isset($matches[1]) ? (int)$matches[1] : null;
                }
                
                $ticketGroups[$key] = [
                    'ticket' => $purchaseTicket->ticketType,
                    'quantity' => 0,
                    'price' => $purchaseTicket->original_price,
                    'day' => $dayNumber, // Pass day number instead of day name
                    'day_name' => $purchaseTicket->event_day_name ?? null // Keep original day name for display
                ];
            }
            $ticketGroups[$key]['quantity']++;
        }
        
        // Convert to array for view
        $tickets = array_values($ticketGroups);
        
        // Ensure order has the required fields
        if (!$order->subtotal || !$order->service_fee || !$order->tax_amount || !$order->total_amount) {
            // Recalculate if missing
            $subtotal = $order->tickets->sum('price_paid');
            $serviceFee = $this->calculateServiceFee($subtotal);
            $taxAmount = $this->calculateTax($subtotal + $serviceFee);
            $totalAmount = $subtotal + $serviceFee + $taxAmount;
            
            // Update order with calculated values
            $order->update([
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);
        }
        
        return view('public.tickets.success', compact('order', 'event', 'tickets', 'totalQuantity', 'serviceFeePercentage', 'taxPercentage', 'comboDiscountAmount', 'originalSubtotal'));
    }

    /**
     * Show purchase failure page
     */
    public function failure(Event $event, Request $request)
    {
        $error_message = $request->get('error_message', 'Payment processing failed');
        $error_code = $request->get('error_code', 'PAYMENT_FAILED');
        $transaction_id = $request->get('transaction_id', 'N/A');
        
        return view('public.tickets.failure', compact('event', 'error_message', 'error_code', 'transaction_id'));
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
     * Calculate service fee based on settings
     */
    private function calculateServiceFee(float $subtotal): float
    {
        $serviceFeePercentage = Setting::get('service_fee_percentage', 5.0);
        return round($subtotal * ($serviceFeePercentage / 100), 2);
    }

    /**
     * Calculate tax based on settings
     */
    private function calculateTax(float $subtotal): float
    {
        $taxPercentage = Setting::get('tax_percentage', 6.0);
        return round($subtotal * ($taxPercentage / 100), 2);
    }
}