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

            // Check if user has any pending orders
            $pendingOrder = Order::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if ($pendingOrder) {
                return redirect()->route('public.tickets.cart', $event)
                    ->with('pending_order', true)
                    ->with('pending_order_id', $pendingOrder->id)
                    ->with('pending_order_number', $pendingOrder->order_number)
                    ->with('pending_payment_url', route('public.tickets.payment', $pendingOrder));
            }

            if (!$event->isOnSale()) {
                return redirect()->route('public.events.show', $event)
                    ->with('error', 'This event is not currently on sale.');
            }

            // Handle POST request (form submission from cart)
            if ($request->isMethod('post')) {
                // Validate the form data
                $request->validate([
                    'purchase_type' => 'required|in:single_day,multi_day',
                ]);
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
            
            // Only validate if it's a POST request
            if ($request->isMethod('post')) {
                // Only validate if it's a POST request
            if ($request->isMethod('post')) {
            $request->validate($validationRules);
            }
            }
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
            
            // Only validate if it's a POST request
            if ($request->isMethod('post')) {
            $request->validate($validationRules);
            }
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
            // Multi-day purchase - BOTH DAYS MUST BE SELECTED
            $day1Enabled = $request->boolean('multi_day1_enabled');
            $day2Enabled = $request->boolean('multi_day2_enabled');
            
            // Validate that both days are selected for multi-day purchase
            if (!$day1Enabled || !$day2Enabled) {
                return redirect()->back()
                    ->with('error', 'Multi-day purchase requires selecting both Day 1 and Day 2 tickets.')
                    ->withInput();
            }
            
            // Both days are required
            $day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
            $day1Quantity = $request->day1_quantity ?? 1;
            $tickets[] = ['ticket' => $day1Ticket, 'price' => $day1Ticket->price, 'quantity' => $day1Quantity, 'day' => 1];
            $totalQuantity += $day1Quantity;
            
            $day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
            $day2Quantity = $request->day2_quantity ?? 1;
            $tickets[] = ['ticket' => $day2Ticket, 'price' => $day2Ticket->price, 'quantity' => $day2Quantity, 'day' => 2];
            $totalQuantity += $day2Quantity;
            
            // Calculate subtotal
            foreach ($tickets as $ticketData) {
                $subtotal += $ticketData['price'] * $ticketData['quantity'];
            }
            
            // Apply combo discount if both days are enabled and have tickets
            if ($event->combo_discount_enabled) {
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

        // Handle POST request - create order and redirect to payment
        if ($request->isMethod('post')) {
            // Validate the form data
            $validationRules = [
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
            ];
            
            if ($purchaseType === 'single_day') {
                $validationRules['ticket_type_id'] = 'required|integer|exists:tickets,id';
                $validationRules['quantity'] = 'required|integer|min:1|max:10';
            } else {
                $day1Enabled = $request->boolean('multi_day1_enabled');
                $day2Enabled = $request->boolean('multi_day2_enabled');
                
                if ($day1Enabled) {
                    $validationRules['day1_ticket_type'] = 'required|integer|exists:tickets,id';
                    $validationRules['day1_quantity'] = 'required|integer|min:1|max:10';
                }
                if ($day2Enabled) {
                    $validationRules['day2_ticket_type'] = 'required|integer|exists:tickets,id';
                    $validationRules['day2_quantity'] = 'required|integer|min:1|max:10';
                }
            }
            
            $request->validate($validationRules);
            
            // Create the order directly in the checkout method
            DB::beginTransaction();
            try {
                // Create the order
                $order = \App\Models\Order::create([
                    'user_id' => Auth::id(),
                    'event_id' => $event->id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'purchase_type' => $purchaseType,
                    'order_number' => \App\Models\Order::generateOrderNumber(),
                    'qrcode' => \App\Models\Order::generateQRCode(),
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'service_fee' => $serviceFee,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_method' => 'stripe',
                ]);
                
                // Create order items (purchase tickets)
                $comboGroupId = ($purchaseType === 'multi_day') ? 'COMBO_' . $order->id . '_' . time() : null;
                $eventDays = $event->getEventDays();
                
                foreach ($tickets as $ticketData) {
                    $ticket = $ticketData['ticket'];
                    $quantity = $ticketData['quantity'];
                    $price = $ticketData['price'];
                    $eventDay = $ticketData['day'] ?? 1;
                    $eventDayName = $ticketData['day'] ? 'Day ' . $ticketData['day'] : 'Day 1';
                    
                    // Calculate per-ticket price (after discount if applicable)
                    $perTicketPrice = $price;
                    if ($discountAmount > 0 && $totalQuantity > 0) {
                        // Distribute discount proportionally based on quantity
                        $perTicketPrice = ($subtotal / $totalQuantity) + $discountAmount / $totalQuantity;
                    }
                    
                    for ($i = 0; $i < $quantity; $i++) {
                        \App\Models\PurchaseTicket::create([
                            'order_id' => $order->id,
                            'event_id' => $event->id,
                            'ticket_type_id' => $ticket->id,
                            'zone' => $ticket->name,
                            'event_day' => $eventDay,
                            'event_day_name' => $eventDayName,
                            'is_combo_purchase' => $purchaseType === 'multi_day',
                            'combo_group_id' => $comboGroupId,
                            'original_price' => $price,
                            'discount_amount' => $discountAmount > 0 ? ($discountAmount / $totalQuantity) : 0,
                            'qrcode' => \App\Models\PurchaseTicket::generateQRCode(),
                            'status' => 'pending',
                            'price_paid' => $perTicketPrice,
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
                
                // Redirect to payment page
                return redirect()->route('public.tickets.payment', $order);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Order creation error in checkout', [
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Failed to create order. Please try again.')
                    ->withInput();
            }
        }
        
        return view('public.tickets.checkout-stripe', compact('event', 'tickets', 'purchaseType', 'subtotal', 'discountAmount', 'serviceFee', 'taxAmount', 'totalAmount', 'totalQuantity', 'serviceFeePercentage', 'taxPercentage'));
        
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
     * Create order from checkout form data
     */
    private function createOrderFromRequest($request, $event, $tickets, $purchaseType, $subtotal, $discountAmount, $serviceFee, $taxAmount, $totalAmount, $totalQuantity)
    {
        // Create the order
        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'purchase_type' => $purchaseType,
            'order_number' => \App\Models\Order::generateOrderNumber(),
            'qrcode' => \App\Models\Order::generateQRCode(),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'service_fee' => $serviceFee,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'stripe',
        ]);
        
        // Create order items (purchase tickets)
        $comboGroupId = ($purchaseType === 'multi_day') ? 'COMBO_' . $order->id . '_' . time() : null;
        $eventDays = $event->getEventDays();
        
        foreach ($tickets as $ticketData) {
            $ticket = $ticketData['ticket'];
            $quantity = $ticketData['quantity'];
            $price = $ticketData['price'];
            $day = $ticketData['day'] ?? 1;
            
            // Determine event day information
            $eventDay = null;
            $eventDayName = null;
            if ($event->isMultiDay() && isset($eventDays[$day - 1])) {
                $eventDay = $eventDays[$day - 1]['date'];
                $eventDayName = $eventDays[$day - 1]['day_name'];
            } elseif (count($eventDays) > 0) {
                $eventDay = $eventDays[0]['date'];
                $eventDayName = $eventDays[0]['day_name'];
            }
            
            // Create individual purchase tickets for each quantity
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
        
        return $order;
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

        // Check if user has any pending orders
        $pendingOrder = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($pendingOrder) {
            return response()->json([
                'success' => false,
                'error' => 'You have a pending order that needs to be completed first.',
                'pending_order_id' => $pendingOrder->id,
                'pending_order_number' => $pendingOrder->order_number,
                'redirect_url' => route('public.tickets.payment', $pendingOrder)
            ], 400);
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
            
            // Only validate if it's a POST request
            if ($request->isMethod('post')) {
            $request->validate($validationRules);
            }
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
            
            // Only validate if it's a POST request
            if ($request->isMethod('post')) {
            $request->validate($validationRules);
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

            // Return JSON response for AJAX
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase failed', [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Return JSON error response
            return response()->json([
                'success' => false,
                'error' => 'Failed to process purchase: ' . $e->getMessage(),
                'error_code' => 'PURCHASE_ERROR'
            ], 500);
        }
    }

    /**
     * Show loading page before redirecting to payment
     */
    public function loading(Order $order)
    {
        // Check if user is authenticated and owns this order
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access your order.');
        }

        // Check if order is in pending status
        if ($order->status !== 'pending') {
            if ($order->status === 'paid') {
                return redirect()->route('public.tickets.success', $order)
                    ->with('info', 'This order has already been paid.');
            } else {
                return redirect()->route('public.tickets.failure', $order->event)
                    ->with('error', 'This order is no longer available for payment.');
            }
        }

        return view('payment.loading', compact('order'));
    }

    /**
     * Show payment page for Stripe integration
     */
    public function payment(Order $order)
    {
        // Check if user is authenticated and owns this order
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access your order.');
        }

        // Check if order is in pending status
        if ($order->status !== 'pending') {
            if ($order->status === 'paid') {
                return redirect()->route('public.tickets.success', $order)
                    ->with('info', 'This order has already been paid.');
            } else {
                return redirect()->route('public.tickets.failure', $order->event)
                    ->with('error', 'This order is no longer available for payment.');
            }
        }

        // Check if order has timed out (15 minutes)
        $timeoutMinutes = 15;
        $timeoutThreshold = now()->subMinutes($timeoutMinutes);
        
        if ($order->created_at->lt($timeoutThreshold)) {
            // Order has timed out - cancel it and redirect to cart with timeout message
            try {
                DB::beginTransaction();
                
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $timeoutMinutes . ' minutes'
                ]);
                
                // Update purchase ticket statuses to cancelled
                $order->purchaseTickets()->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $timeoutMinutes . ' minutes'
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
                
                DB::commit();
                
                Log::info('Order cancelled due to payment timeout (user access)', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'created_at' => $order->created_at,
                    'cancelled_at' => now()
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to cancel expired order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            // Redirect to cart with timeout message
            return redirect()->route('public.tickets.cart', $order->event)
                ->with('timeout', true)
                ->with('timeout_order_id', $order->id)
                ->with('error', 'Your order has expired due to payment timeout. Please select your tickets again.');
        }

        // Load order with relationships
        $order->load(['purchaseTickets.ticketType.event', 'user']);

        return view('payment.stripe-checkout', compact('order'));
    }

    /**
     * Show purchase success page
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Update order status to paid if it's still pending
        if ($order->status === 'pending') {
            DB::beginTransaction();
            try {
                // Update order status
                $order->update([
                    'status' => 'paid',
                    'payment_method' => 'stripe',
                    'paid_at' => now(),
                ]);
                
                // Update all purchase ticket statuses to 'active'
                $order->purchaseTickets()->update(['status' => 'active']);
                
                // Create payment record
                \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'method' => 'stripe',
                    'amount' => $order->total_amount,
                    'currency' => 'myr',
                    'status' => 'completed',
                    'processed_at' => now(),
                    'payment_date' => now(),
                    'transaction_id' => 'STRIPE_' . $order->id . '_' . time(),
                    'notes' => 'Payment completed via Stripe payment gateway',
                ]);
                
                DB::commit();
                
                // Log the payment
                \Log::info('Order payment completed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'total_amount' => $order->total_amount,
                    'payment_method' => 'stripe'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Payment processing error', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Still show success page but log the error
            }
        }

        $order->load(['purchaseTickets.ticketType', 'user', 'event']);
        $event = $order->event;
        
        // Calculate totals for display
        $totalQuantity = $order->purchaseTickets->count(); // Count of purchase tickets
        
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

        $order->load([
            'tickets',
            'purchaseTickets.event',
            'purchaseTickets.ticketType',
            'user',
            'event'
        ]);
        
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

    /**
     * Check if an order is still valid for payment
     */
    public function checkOrderStatus(Order $order)
    {
        // Check if user is authenticated and owns this order
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return response()->json([
                'valid' => false,
                'message' => 'Order not found or access denied'
            ], 403);
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return response()->json([
                'valid' => false,
                'message' => 'Order is no longer pending',
                'status' => $order->status
            ]);
        }

        // Check if order has timed out (15 minutes)
        $timeoutMinutes = 15;
        $timeoutThreshold = now()->subMinutes($timeoutMinutes);
        
        if ($order->created_at->lt($timeoutThreshold)) {
            // Order has timed out - cancel it
            try {
                DB::beginTransaction();
                
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $timeoutMinutes . ' minutes'
                ]);
                
                // Update purchase ticket statuses to cancelled
                $order->purchaseTickets()->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Payment timeout - exceeded ' . $timeoutMinutes . ' minutes'
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
                
                DB::commit();
                
                return response()->json([
                    'valid' => false,
                    'message' => 'Order has expired due to payment timeout',
                    'status' => 'cancelled'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to cancel expired order during status check', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'valid' => false,
                    'message' => 'Order validation failed'
                ], 500);
            }
        }

        // Order is valid
        return response()->json([
            'valid' => true,
            'message' => 'Order is valid for payment',
            'status' => $order->status,
            'created_at' => $order->created_at,
            'timeout_at' => $order->created_at->addMinutes($timeoutMinutes)
        ]);
    }
}