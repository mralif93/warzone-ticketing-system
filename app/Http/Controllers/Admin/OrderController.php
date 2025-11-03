<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'purchaseTickets']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $orders = $query->latest()->paginate($perPage);
        $statuses = Order::select('status')->distinct()->pluck('status');

        // Calculate additional statistics from ALL orders (not paginated)
        $allOrdersQuery = Order::query();
        
        // Apply the same filters as the main query
        if ($request->filled('search')) {
            $search = $request->search;
            $allOrdersQuery->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status')) {
            $allOrdersQuery->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $allOrdersQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $allOrdersQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $allOrders = $allOrdersQuery->get();
        $totalRevenue = $allOrders->where('status', 'paid')->sum('total_amount');
        $averageOrderValue = $allOrders->where('status', 'paid')->avg('total_amount');
        
        // Calculate counts from all orders (not just paginated)
        $totalOrders = $allOrders->count();
        $paidOrdersCount = $allOrders->where('status', 'paid')->count();
        $pendingOrdersCount = $allOrders->where('status', 'pending')->count();
        $cancelledOrdersCount = $allOrders->where('status', 'cancelled')->count();
        $refundedOrdersCount = $allOrders->where('status', 'refunded')->count();

        return view('admin.orders.index', compact('orders', 'statuses', 'totalRevenue', 'averageOrderValue', 'totalOrders', 'paidOrdersCount', 'pendingOrdersCount', 'cancelledOrdersCount', 'refundedOrdersCount'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $users = \App\Models\User::all();
        $events = \App\Models\Event::where('status', 'on_sale')->get()->map(function($event) {
            $event->is_multi_day = $event->isMultiDay();
            $event->combo_discount_enabled = $event->combo_discount_enabled;
            $event->combo_discount_percentage = $event->combo_discount_percentage;
            $event->event_days = $event->getEventDays();
            return $event;
        });
        
        // Get service fee and tax settings
        $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0);
        $taxPercentage = Setting::get('tax_percentage', 0.0);
        
        return view('admin.orders.create', compact('users', 'events', 'serviceFeePercentage', 'taxPercentage'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Order creation request:', $request->all());
        
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'event_id' => 'required|exists:events,id',
                'customer_email' => 'required|email|max:255',
                'payment_method' => 'required|string|in:bank_transfer,online_banking,qr_code_ewallet,debit_credit_card,others',
                'status' => 'required|in:pending,paid,cancelled,refunded',
                'purchase_type' => 'required|in:single_day,multi_day,day1_only,day2_only',
                'single_day_type' => 'required_if:purchase_type,single_day|in:day1,day2',
                'multi_day1_enabled' => 'boolean',
                'multi_day2_enabled' => 'boolean',
                'ticket_type_id' => 'required_if:purchase_type,single_day|nullable|exists:tickets,id',
                'day1_ticket_type' => 'required_if:multi_day1_enabled,1|nullable|exists:tickets,id',
                'day2_ticket_type' => 'required_if:multi_day2_enabled,1|nullable|exists:tickets,id',
                'day1_only_ticket_type' => 'required_if:purchase_type,day1_only|exists:tickets,id',
                'day2_only_ticket_type' => 'required_if:purchase_type,day2_only|exists:tickets,id',
                'day1_quantity' => 'required_if:multi_day1_enabled,1|integer|min:1|max:10',
                'day2_quantity' => 'required_if:multi_day2_enabled,1|integer|min:1|max:10',
                'day1_only_quantity' => 'required_if:purchase_type,day1_only|integer|min:1|max:10',
                'day2_only_quantity' => 'required_if:purchase_type,day2_only|integer|min:1|max:10',
                'quantity' => 'required_if:purchase_type,single_day|integer|min:1|max:10',
                'event_day' => 'nullable|date',
                'event_day_name' => 'nullable|string|max:255',
                'is_combo_purchase' => 'boolean',
                'notes' => 'nullable|string|max:1000'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        // Additional validation for multi-day purchases
        if ($request->purchase_type === 'multi_day') {
            $day1Enabled = $request->boolean('multi_day1_enabled');
            $day2Enabled = $request->boolean('multi_day2_enabled');
            
            // Ensure at least one day is selected
            if (!$day1Enabled && !$day2Enabled) {
                return back()->withErrors(['multi_day1_enabled' => 'Please select at least one day to attend.'])
                    ->withInput();
            }
            
            // Validate quantities only for enabled days
            if ($day1Enabled) {
                $day1Quantity = $request->day1_quantity ?? 1;
                if ($day1Quantity < 1 || $day1Quantity > 10) {
                    return back()->withErrors(['day1_quantity' => 'Day 1 quantity must be between 1-10 tickets.'])
                        ->withInput();
                }
            }
            
            if ($day2Enabled) {
                $day2Quantity = $request->day2_quantity ?? 1;
                if ($day2Quantity < 1 || $day2Quantity > 10) {
                    return back()->withErrors(['day2_quantity' => 'Day 2 quantity must be between 1-10 tickets.'])
                        ->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            // Get event and user
            $event = \App\Models\Event::findOrFail($request->event_id);
            $user = \App\Models\User::findOrFail($request->user_id);
            
            $purchaseType = $request->purchase_type;
            $tickets = [];
            $totalQuantity = 0;
            $subtotal = 0;
            $discountAmount = 0;
            
            // Handle different purchase types
            if ($purchaseType === 'day1_only') {
                // Day 1 Only purchase
                $day1OnlyTicket = \App\Models\Ticket::findOrFail($request->day1_only_ticket_type);
                $day1OnlyQuantity = $request->day1_only_quantity ?? 1;
                
                // Verify ticket type belongs to selected event
                if ($day1OnlyTicket->event_id != $event->id) {
                    return back()->withErrors(['day1_only_ticket_type' => 'Selected ticket type does not belong to the selected event.'])
                        ->withInput();
                }
                
                // Check availability
                if ($day1OnlyTicket->available_seats < $day1OnlyQuantity) {
                    return back()->withErrors(['day1_only_ticket_type' => 'Insufficient tickets available for Day 1.'])
                        ->withInput();
                }
                
                // Calculate pricing
                $day1OnlyPrice = $day1OnlyTicket->price;
                $subtotal = $day1OnlyPrice * $day1OnlyQuantity;
                $totalQuantity = $day1OnlyQuantity;
                
                // Create purchase ticket for Day 1
                $tickets[] = [
                    'ticket_type_id' => $day1OnlyTicket->id,
                    'quantity' => $day1OnlyQuantity,
                    'price_paid' => $day1OnlyPrice,
                    'event_day' => $event->getEventDays()[0] ?? $event->date_time,
                    'event_day_name' => 'Day 1'
                ];
                
            } elseif ($purchaseType === 'day2_only') {
                // Day 2 Only purchase
                $day2OnlyTicket = \App\Models\Ticket::findOrFail($request->day2_only_ticket_type);
                $day2OnlyQuantity = $request->day2_only_quantity ?? 1;
                
                // Verify ticket type belongs to selected event
                if ($day2OnlyTicket->event_id != $event->id) {
                    return back()->withErrors(['day2_only_ticket_type' => 'Selected ticket type does not belong to the selected event.'])
                        ->withInput();
                }
                
                // Check availability
                if ($day2OnlyTicket->available_seats < $day2OnlyQuantity) {
                    return back()->withErrors(['day2_only_ticket_type' => 'Insufficient tickets available for Day 2.'])
                        ->withInput();
                }
                
                // Calculate pricing
                $day2OnlyPrice = $day2OnlyTicket->price;
                $subtotal = $day2OnlyPrice * $day2OnlyQuantity;
                $totalQuantity = $day2OnlyQuantity;
                
                // Create purchase ticket for Day 2
                $tickets[] = [
                    'ticket_type_id' => $day2OnlyTicket->id,
                    'quantity' => $day2OnlyQuantity,
                    'price_paid' => $day2OnlyPrice,
                    'event_day' => $event->getEventDays()[1] ?? $event->date_time,
                    'event_day_name' => 'Day 2'
                ];
                
            } elseif ($purchaseType === 'multi_day') {
                // Multi-day purchase with flexible day selection
                $day1Enabled = $request->boolean('multi_day1_enabled');
                $day2Enabled = $request->boolean('multi_day2_enabled');
                $subtotal = 0;
                $totalQuantity = 0;
                $tickets = [];
                
                if ($day1Enabled) {
                    $day1Ticket = \App\Models\Ticket::findOrFail($request->day1_ticket_type);
                    $day1Quantity = $request->day1_quantity ?? 1;
                    
                    // Verify ticket type belongs to selected event
                    if ($day1Ticket->event_id != $event->id) {
                        return back()->withErrors(['day1_ticket_type' => 'Selected Day 1 ticket type does not belong to the selected event.'])
                            ->withInput();
                    }
                    
                    // Check availability
                    if ($day1Ticket->available_seats < $day1Quantity) {
                        return back()->withErrors(['day1_ticket_type' => 'Insufficient tickets available for Day 1.'])
                            ->withInput();
                    }
                    
                    // Calculate pricing
                    $day1Price = $day1Ticket->price;
                    $subtotal += $day1Price * $day1Quantity;
                    $totalQuantity += $day1Quantity;
                    
                    // Add to tickets array
                    $tickets[] = ['ticket' => $day1Ticket, 'price' => $day1Price, 'quantity' => $day1Quantity, 'day' => 1];
                }
                
                if ($day2Enabled) {
                    $day2Ticket = \App\Models\Ticket::findOrFail($request->day2_ticket_type);
                    $day2Quantity = $request->day2_quantity ?? 1;
                    
                    // Verify ticket type belongs to selected event
                    if ($day2Ticket->event_id != $event->id) {
                        return back()->withErrors(['day2_ticket_type' => 'Selected Day 2 ticket type does not belong to the selected event.'])
                            ->withInput();
                    }
                    
                    // Check availability
                    if ($day2Ticket->available_seats < $day2Quantity) {
                        return back()->withErrors(['day2_ticket_type' => 'Insufficient tickets available for Day 2.'])
                            ->withInput();
                    }
                    
                    // Calculate pricing
                    $day2Price = $day2Ticket->price;
                    $subtotal += $day2Price * $day2Quantity;
                    $totalQuantity += $day2Quantity;
                    
                    // Add to tickets array
                    $tickets[] = ['ticket' => $day2Ticket, 'price' => $day2Price, 'quantity' => $day2Quantity, 'day' => 2];
                }
                
                // Ensure at least one day is selected
                if (!$day1Enabled && !$day2Enabled) {
                    return back()->withErrors(['multi_day1_enabled' => 'Please select at least one day to attend.'])
                        ->withInput();
                }
                
                // Store original subtotal before applying discount
                $originalSubtotal = $subtotal;
                
                // Apply combo discount if both days are selected and enabled
                $isCombo = $request->boolean('is_combo_purchase') && $day1Enabled && $day2Enabled && $event->isTwoDayEvent() && $event->combo_discount_enabled;
                if ($isCombo) {
                    $discountAmount = $event->calculateComboDiscount($subtotal);
                    $subtotal = $subtotal - $discountAmount;
                } else {
                    $discountAmount = 0;
                }
                
            } else {
                // Single day purchase with day type selection
                $ticketType = \App\Models\Ticket::findOrFail($request->ticket_type_id);
                $singleDayType = $request->single_day_type ?? 'day1';
                
                // Verify ticket type belongs to selected event
                if ($ticketType->event_id != $event->id) {
                    return back()->withErrors(['ticket_type_id' => 'Selected ticket type does not belong to the selected event.'])
                        ->withInput();
                }
                
                // Check availability
                $quantity = $request->quantity;
                if ($ticketType->available_seats < $quantity) {
                    return back()->withErrors(['quantity' => 'Insufficient tickets available. Only ' . $ticketType->available_seats . ' tickets remaining.'])
                        ->withInput();
                }
                
                // Calculate pricing
                $basePrice = $ticketType->price;
                $subtotal = $basePrice * $quantity;
                
                // Store original subtotal (for single-day, no discount)
                $originalSubtotal = $subtotal;
                $discountAmount = 0;
                
                // Determine event day based on single day type
                $eventDays = $event->getEventDays();
                $eventDay = $singleDayType === 'day1' ? 
                    ($eventDays[0]['date'] ?? $event->date_time) : 
                    ($eventDays[1]['date'] ?? $event->date_time);
                $eventDayName = $singleDayType === 'day1' ? 'Day 1' : 'Day 2';
                
                // Create purchase ticket with correct day assignment
                $tickets[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $quantity,
                    'price_paid' => $basePrice,
                    'event_day' => $eventDay,
                    'event_day_name' => $eventDayName
                ];
                $totalQuantity = $quantity;
            }
            
            // Calculate final pricing
            $serviceFee = $this->calculateServiceFee($subtotal);
            $taxAmount = $this->calculateTax($subtotal + $serviceFee);
            $totalAmount = $subtotal + $serviceFee + $taxAmount;
            
            // Create order
            $order = Order::create([
                'user_id' => $request->user_id,
                'event_id' => $event->id,
                'customer_email' => $request->customer_email,
                'order_number' => Order::generateOrderNumber(),
                'qrcode' => Order::generateQRCode(),
                'subtotal' => isset($originalSubtotal) ? $originalSubtotal : $subtotal,
                'discount_amount' => isset($discountAmount) ? $discountAmount : 0,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create purchase tickets
            $comboGroupId = ($purchaseType === 'multi_day') ? 'COMBO_' . $order->id . '_' . time() : null;
            
            foreach ($tickets as $ticketData) {
                // Handle different ticket data structures
                if (isset($ticketData['ticket'])) {
                    // Multi-day structure
                    $ticket = $ticketData['ticket'];
                    $price = $ticketData['price'];
                    $quantity = $ticketData['quantity'] ?? 1;
                    $day = $ticketData['day'] ?? null;
                    
                    
                    $eventDay = $day ? $event->getEventDays()[$day-1]['date'] : $request->event_day;
                    $eventDayName = $day ? $event->getEventDays()[$day-1]['day_name'] : $request->event_day_name;
                    
                    // Ensure eventDay is a string
                    $eventDay = is_array($eventDay) ? $eventDay['date'] ?? $eventDay[0] ?? $eventDay : $eventDay;
                } else {
                    // Direct structure (single day, day1_only, day2_only)
                    $ticket = \App\Models\Ticket::findOrFail($ticketData['ticket_type_id']);
                    $price = $ticketData['price_paid'];
                    $quantity = $ticketData['quantity'] ?? 1;
                    $eventDay = $ticketData['event_day'];
                    $eventDayName = $ticketData['event_day_name'];
                }
                
                // Calculate per-ticket price with proportional discount
                $perTicketPrice = $price;
                $perTicketDiscount = 0;
                
                if ($discountAmount > 0 && $totalQuantity > 0 && $purchaseType === 'multi_day' && isset($originalSubtotal)) {
                    // Distribute discount proportionally based on ticket price share
                    $ticketTotalValue = $price * $quantity;
                    $priceShare = $ticketTotalValue / $originalSubtotal;
                    $ticketDiscount = $discountAmount * $priceShare;
                    $perTicketDiscount = $ticketDiscount / $quantity;
                    $perTicketPrice = $price - $perTicketDiscount;
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
                        'discount_amount' => $perTicketDiscount,
                        'qrcode' => \App\Models\PurchaseTicket::generateQRCode(),
                        'status' => $request->status === 'paid' ? 'active' : 'pending',
                        'price_paid' => $perTicketPrice,
                    ]);
                }
                
                // Update ticket type availability for all orders (including pending)
                $ticket->update([
                    'sold_seats' => $ticket->sold_seats + $quantity,
                    'available_seats' => $ticket->available_seats - $quantity,
                    'status' => $ticket->available_seats - $quantity <= 0 ? 'sold_out' : 'active',
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE',
                'table_name' => 'orders',
                'record_id' => $order->id,
                'old_values' => null,
                'new_values' => $order->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => "Order created: #{$order->order_number} for {$order->customer_email} - {$totalQuantity} tickets (RM{$order->total_amount})"
            ]);

            DB::commit();

            $purchaseTypeText = $purchaseType === 'multi_day' ? 'tickets (Day 1 & Day 2)' : 'tickets';
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order created successfully with ' . $totalQuantity . ' ' . $purchaseTypeText . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Failed to create order: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'purchaseTickets.ticketType', 'purchaseTickets.event', 'payments', 'event']);

        // Get service fee and tax settings
        $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0);
        $taxPercentage = Setting::get('tax_percentage', 0.0);

        return view('admin.orders.show', compact('order', 'serviceFeePercentage', 'taxPercentage'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $order->load(['purchaseTickets.ticketType', 'user']);
        $users = \App\Models\User::all();
        $events = \App\Models\Event::where('status', 'on_sale')->get();
        
        // Get service fee and tax settings
        $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0);
        $taxPercentage = Setting::get('tax_percentage', 0.0);
        
        return view('admin.orders.edit', compact('order', 'users', 'events', 'serviceFeePercentage', 'taxPercentage'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|string|in:bank_transfer,online_banking,qr_code_ewallet,debit_credit_card,others',
            'status' => 'required|in:pending,paid,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
            'ticket_quantity' => 'nullable|integer|min:1|max:20',
            'ticket_type_id' => 'nullable|exists:tickets,id'
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $order->toArray();
            
            // Update order basic information
            $order->update([
                'user_id' => $request->user_id,
                'customer_email' => $request->customer_email,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Update ticket statuses based on order status
            $ticketStatus = $request->status === 'paid' ? 'active' : 
                           ($request->status === 'cancelled' ? 'cancelled' : 
                           ($request->status === 'refunded' ? 'refunded' : 'pending'));
            
            $order->purchaseTickets()->update(['status' => $ticketStatus]);

            // Handle ticket type availability based on status change
            $this->updateTicketAvailabilityForOrderStatus($order, $request->status, $oldValues['status']);

            // Handle ticket quantity changes
            if ($request->has('ticket_quantity')) {
                $newQuantity = (int) $request->ticket_quantity;
                $currentQuantity = $order->purchaseTickets->count();
                $currentTickets = $order->purchaseTickets;
                
                if ($newQuantity != $currentQuantity) {
                    \Log::info('Updating ticket quantity', [
                        'order_id' => $order->id,
                        'current_quantity' => $currentQuantity,
                        'new_quantity' => $newQuantity
                    ]);
                    
                    if ($newQuantity < $currentQuantity) {
                        // Reduce quantity - delete excess tickets
                        $ticketsToDelete = $currentTickets->take($currentQuantity - $newQuantity);
                        foreach ($ticketsToDelete as $ticket) {
                            // Update ticket type availability
                            $ticketType = $ticket->ticketType;
                            if ($ticketType) {
                                $ticketType->update([
                                    'sold_seats' => $ticketType->sold_seats - 1,
                                    'available_seats' => $ticketType->available_seats + 1,
                                    'status' => $ticketType->available_seats + 1 > 0 ? 'active' : 'sold_out'
                                ]);
                            }
                            $ticket->delete();
                        }
                        
                        // Recalculate order total based on remaining tickets
                        $remainingTickets = $order->purchaseTickets()->get();
                        $subtotal = $remainingTickets->sum('price_paid');
                        $serviceFee = $this->calculateServiceFee($subtotal);
                        $taxAmount = $this->calculateTax($subtotal + $serviceFee);
                        $totalAmount = $subtotal + $serviceFee + $taxAmount;
                        
                    } elseif ($newQuantity > $currentQuantity) {
                        // Increase quantity - add new tickets
                        $additionalTickets = $newQuantity - $currentQuantity;
                        $ticketTypeId = $request->ticket_type_id;
                        
                        if (!$ticketTypeId) {
                            throw new \Exception('Ticket type is required when increasing quantity');
                        }
                        
                        $ticketType = \App\Models\Ticket::findOrFail($ticketTypeId);
                        $event = $order->purchaseTickets->first()->event;
                        
                        // Check availability
                        if ($ticketType->available_seats < $additionalTickets) {
                            throw new \Exception('Insufficient tickets available. Only ' . $ticketType->available_seats . ' tickets remaining.');
                        }
                        
                        // Create additional tickets
                        for ($i = 0; $i < $additionalTickets; $i++) {
                            \App\Models\PurchaseTicket::create([
                                'order_id' => $order->id,
                                'event_id' => $event->id,
                                'ticket_type_id' => $ticketType->id,
                                'zone' => $ticketType->name,
                                'event_day' => $order->purchaseTickets->first()->event_day,
                                'event_day_name' => $order->purchaseTickets->first()->event_day_name,
                                'is_combo_purchase' => $order->purchaseTickets->first()->is_combo_purchase,
                                'combo_group_id' => $order->purchaseTickets->first()->combo_group_id,
                                'original_price' => $ticketType->price,
                                'discount_amount' => 0, // New tickets don't get combo discount
                                'qrcode' => \App\Models\PurchaseTicket::generateQRCode(),
                                'status' => 'active',
                                'price_paid' => $ticketType->price,
                            ]);
                        }
                        
                        // Update ticket type availability
                        $ticketType->update([
                            'sold_seats' => $ticketType->sold_seats + $additionalTickets,
                            'available_seats' => $ticketType->available_seats - $additionalTickets,
                            'status' => $ticketType->available_seats - $additionalTickets <= 0 ? 'sold_out' : 'active'
                        ]);
                        
                        // Recalculate order total
                        $allTickets = $order->purchaseTickets()->get();
                        $subtotal = $allTickets->sum('price_paid');
                        $serviceFee = $this->calculateServiceFee($subtotal);
                        $taxAmount = $this->calculateTax($subtotal + $serviceFee);
                        $totalAmount = $subtotal + $serviceFee + $taxAmount;
                    }
                    
                    // Update order pricing
                    $order->update([
                        'subtotal' => $subtotal,
                        'service_fee' => $serviceFee,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                    ]);
                }
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'UPDATE',
                'table_name' => 'orders',
                'record_id' => $order->id,
                'old_values' => $oldValues,
                'new_values' => $order->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update order. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified order from storage
     */
    public function destroy(Order $order)
    {
        if ($order->status === 'paid') {
            return back()->withErrors(['error' => 'Cannot delete a paid order.']);
        }

        $oldValues = $order->toArray();
        $order->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Cancelled,Refunded'
        ]);

        $oldValues = $order->toArray();
        $order->update(['status' => $request->status]);

        // Log the status update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'paid') {
            return back()->withErrors(['error' => 'Cannot cancel a paid order. Use refund instead.']);
        }

        $oldValues = $order->toArray();
        $order->update(['status' => 'cancelled']);

        // Cancel all tickets in this order
        $order->tickets()->update(['status' => 'cancelled']);

        // Log the order cancellation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Order cancelled successfully!');
    }

    /**
     * Refund order
     */
    public function refund(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->withErrors(['error' => 'Only paid orders can be refunded.']);
        }

        $oldValues = $order->toArray();
        $order->update(['status' => 'refunded']);

        // Cancel all tickets in this order
        $order->tickets()->update(['status' => 'cancelled']);

        // Log the order refund
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'orders',
            'record_id' => $order->id,
            'old_values' => $oldValues,
            'new_values' => $order->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Order refunded successfully!');
    }

    /**
     * Get zone price
     */
    private function getZonePrice($zone)
    {
        $zonePrices = [
            'Warzone Exclusive' => 500,
            'Warzone VIP' => 250,
            'Warzone Grandstand' => 199,
            'Warzone Premium Ringside' => 150,
            'Level 1 Zone A/B/C/D' => 100,
            'Level 2 Zone A/B/C/D' => 75,
            'Standing Zone A/B' => 50,
        ];

        return $zonePrices[$zone] ?? 0;
    }

    /**
     * Calculate service fee based on settings
     */
    private function calculateServiceFee($subtotal)
    {
        $serviceFeePercentage = Setting::get('service_fee_percentage', 5.0);
        return round($subtotal * ($serviceFeePercentage / 100), 2);
    }

    /**
     * Calculate tax based on settings
     */
    private function calculateTax($amount)
    {
        $taxPercentage = Setting::get('tax_percentage', 6.0);
        return round($amount * ($taxPercentage / 100), 2);
    }

    /**
     * Update ticket type availability based on order status changes
     */
    private function updateTicketAvailabilityForOrderStatus($order, $newStatus, $oldStatus)
    {
        // Handle availability changes based on status transitions
        if ($newStatus === 'cancelled' || $newStatus === 'refunded') {
            // Order cancelled or refunded - restore availability
            $this->restoreTicketAvailability($order);
        } elseif ($oldStatus === 'cancelled' || $oldStatus === 'refunded') {
            // Order was cancelled/refunded but now active - reduce availability
            $this->reduceTicketAvailability($order);
        }
        // Note: Pending and Paid orders both reduce availability, so no change needed between them
    }

    /**
     * Reduce ticket type availability for orders
     */
    private function reduceTicketAvailability($order)
    {
        $ticketCounts = $order->purchaseTickets->groupBy('ticket_type_id')->map(function ($tickets) {
            return $tickets->count();
        });

        foreach ($ticketCounts as $ticketTypeId => $count) {
            $ticketType = \App\Models\Ticket::find($ticketTypeId);
            if ($ticketType) {
                $ticketType->update([
                    'sold_seats' => $ticketType->sold_seats + $count,
                    'available_seats' => $ticketType->available_seats - $count,
                    'status' => $ticketType->available_seats - $count <= 0 ? 'sold_out' : 'active',
                ]);
            }
        }
    }

    /**
     * Restore ticket type availability for cancelled/refunded orders
     */
    private function restoreTicketAvailability($order)
    {
        $ticketCounts = $order->purchaseTickets->groupBy('ticket_type_id')->map(function ($tickets) {
            return $tickets->count();
        });

        foreach ($ticketCounts as $ticketTypeId => $count) {
            $ticketType = \App\Models\Ticket::find($ticketTypeId);
            if ($ticketType) {
                $ticketType->update([
                    'sold_seats' => max(0, $ticketType->sold_seats - $count),
                    'available_seats' => $ticketType->available_seats + $count,
                    'status' => 'active', // Reset to active when availability is restored
                ]);
            }
        }
    }

    /**
     * Display a listing of trashed orders
     */
    public function trashed(Request $request)
    {
        $query = Order::onlyTrashed()->with(['user', 'tickets']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $orders = $query->orderBy('deleted_at', 'desc')->paginate($perPage);

        // Statistics
        $totalTrashed = Order::onlyTrashed()->count();
        $recentlyDeleted = Order::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        return view('admin.orders.trashed', compact('orders', 'totalTrashed', 'recentlyDeleted'));
    }

    /**
     * Restore a trashed order
     */
    public function restore(Order $order)
    {
        $order->restore();

        return redirect()->route('admin.orders.trashed')
                        ->with('success', 'Order restored successfully.');
    }

    /**
     * Permanently delete a trashed order
     */
    public function forceDelete(Order $order)
    {
        $orderNumber = $order->order_number;
        $order->forceDelete();

        return redirect()->route('admin.orders.trashed')
                        ->with('success', "Order '{$orderNumber}' permanently deleted.");
    }
}
