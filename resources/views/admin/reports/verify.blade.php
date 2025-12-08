<!DOCTYPE html>
<html>
<head>
    <title>Data Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<h1 class="text-2xl font-bold mb-4">📊 Data Verification - Orders, Payments & Tickets</h1>
<p class="text-gray-600 mb-4">Open browser console (F12) to see detailed data</p>

@php
    // ORDERS
    $ordersByStatus = \App\Models\Order::selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
        ->groupBy('status')->get()->keyBy('status');
    $paidOrders = $ordersByStatus->get('paid');
    $pendingOrders = $ordersByStatus->get('pending');
    $cancelledOrders = $ordersByStatus->get('cancelled');

    // PAYMENTS
    $paymentsByStatus = \App\Models\Payment::selectRaw('status, COUNT(*) as count, SUM(amount) as total')
        ->groupBy('status')->get()->keyBy('status');
    $succeededPayments = $paymentsByStatus->get('succeeded');

    $paymentsByMethod = \App\Models\Payment::where('status', 'succeeded')
        ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
        ->groupBy('method')->get();

    // TICKETS BY STATUS
    $ticketsByStatus = \App\Models\PurchaseTicket::selectRaw('status, COUNT(*) as count, SUM(price_paid) as total')
        ->groupBy('status')->get()->keyBy('status');

    // TICKETS FROM PAID ORDERS
    $ticketsFromPaidOrders = \App\Models\PurchaseTicket::whereHas('order', fn($q) => $q->where('status', 'paid'))
        ->selectRaw('COUNT(*) as count, SUM(price_paid) as total')->first();

    // TICKETS BY TICKET TYPE (from paid orders)
    $ticketsByType = \App\Models\PurchaseTicket::whereHas('order', fn($q) => $q->where('status', 'paid'))
        ->join('tickets', 'purchase.ticket_type_id', '=', 'tickets.id')
        ->selectRaw('tickets.name as ticket_name, tickets.total_seats, COUNT(purchase.id) as sold_count, SUM(purchase.price_paid) as total_revenue')
        ->groupBy('tickets.id', 'tickets.name', 'tickets.total_seats')
        ->get();

    // TICKETS BY EVENT DAY (from paid orders)
    $ticketsByDay = \App\Models\PurchaseTicket::whereHas('order', fn($q) => $q->where('status', 'paid'))
        ->selectRaw('event_day_name, COUNT(*) as count, SUM(price_paid) as total')
        ->groupBy('event_day_name')
        ->get();

    // ALL TICKETS DETAILED (from paid orders) - for console
    $allTicketsDetailed = \App\Models\PurchaseTicket::whereHas('order', fn($q) => $q->where('status', 'paid'))
        ->with(['order:id,order_number,status', 'ticketType:id,name,price'])
        ->select('id', 'order_id', 'ticket_type_id', 'event_day_name', 'price_paid', 'status', 'created_at')
        ->orderBy('created_at')
        ->get()
        ->map(fn($t) => [
            'ticket_id' => $t->id,
            'order_number' => $t->order->order_number ?? 'N/A',
            'ticket_type' => $t->ticketType->name ?? 'N/A',
            'event_day' => $t->event_day_name,
            'price_paid' => $t->price_paid,
            'status' => $t->status,
            'created_at' => $t->created_at->format('Y-m-d H:i:s'),
        ]);

    // DISCREPANCIES
    $paidOrdersNoPayment = \App\Models\Order::where('status', 'paid')
        ->whereDoesntHave('payments', fn($q) => $q->where('status', 'succeeded'))
        ->with(['purchaseTickets.ticketType:id,name'])
        ->select('id', 'order_number', 'status', 'total_amount', 'payment_method', 'created_at')
        ->orderBy('created_at')->get()
        ->map(function($order) {
            $ticketTypes = $order->purchaseTickets->map(fn($pt) => $pt->ticketType->name ?? 'N/A')->unique()->implode(', ');
            $ticketStatuses = $order->purchaseTickets->map(fn($pt) => $pt->status)->unique()->implode(', ');
            $ticketCount = $order->purchaseTickets->count();
            return (object)[
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'created_at' => $order->created_at,
                'ticket_types' => $ticketTypes,
                'ticket_statuses' => $ticketStatuses,
                'ticket_count' => $ticketCount,
            ];
        });

    $unpaidOrdersWithPayment = \App\Models\Order::where('status', '!=', 'paid')
        ->whereHas('payments', fn($q) => $q->where('status', 'succeeded'))
        ->select('id', 'order_number', 'status', 'total_amount', 'created_at')->get();

    $totalRefunds = \App\Models\Payment::where('refund_amount', '>', 0)->sum('refund_amount');

    // For console
    $consoleData = [
        'ORDERS' => $ordersByStatus->map(fn($o) => ['count' => $o->count, 'total' => $o->total]),
        'PAYMENTS' => $paymentsByStatus->map(fn($p) => ['count' => $p->count, 'total' => $p->total]),
        'PAYMENTS_BY_METHOD' => $paymentsByMethod,
        'TICKETS_BY_STATUS' => $ticketsByStatus->map(fn($t) => ['count' => $t->count, 'total' => $t->total]),
        'TICKETS_FROM_PAID_ORDERS' => $ticketsFromPaidOrders,
        'TICKETS_BY_TYPE' => $ticketsByType,
        'TICKETS_BY_DAY' => $ticketsByDay,
        'ALL_TICKETS_DETAILED' => $allTicketsDetailed,
        'PAID_ORDERS_NO_PAYMENT' => $paidOrdersNoPayment,
        'UNPAID_ORDERS_WITH_PAYMENT' => $unpaidOrdersWithPayment,
        'REFUNDS' => $totalRefunds,
    ];
@endphp

<script>
console.log('========== 📊 DATA VERIFICATION ==========');
console.log('');
console.log('===== 📦 ORDERS BY STATUS =====');
console.table(@json($consoleData['ORDERS']));
console.log('');
console.log('===== 💳 PAYMENTS BY STATUS =====');
console.table(@json($consoleData['PAYMENTS']));
console.log('');
console.log('===== 💰 PAYMENTS BY METHOD (Succeeded) =====');
console.table(@json($consoleData['PAYMENTS_BY_METHOD']));
console.log('');
console.log('===== 🎫 TICKETS BY STATUS (ALL) =====');
console.table(@json($consoleData['TICKETS_BY_STATUS']));
console.log('');
console.log('===== ✅ TICKETS FROM PAID ORDERS SUMMARY =====');
console.log(@json($consoleData['TICKETS_FROM_PAID_ORDERS']));
console.log('');
console.log('===== 🎫 TICKETS BY TYPE (Paid Orders) =====');
console.table(@json($consoleData['TICKETS_BY_TYPE']));
console.log('');
console.log('===== 📅 TICKETS BY EVENT DAY (Paid Orders) =====');
console.table(@json($consoleData['TICKETS_BY_DAY']));
console.log('');
console.log('===== 📋 ALL TICKETS DETAILED (Paid Orders) - ' + @json($allTicketsDetailed->count()) + ' tickets =====');
console.table(@json($consoleData['ALL_TICKETS_DETAILED']));
console.log('');
console.log('===== ⚠️ PAID ORDERS WITHOUT PAYMENT (' + @json($paidOrdersNoPayment->count()) + ') =====');
console.table(@json($consoleData['PAID_ORDERS_NO_PAYMENT']));
console.log('Total Missing: RM ' + @json($paidOrdersNoPayment->sum('total_amount')));
console.log('');
console.log('===== ⚠️ UNPAID ORDERS WITH PAYMENT =====');
console.table(@json($consoleData['UNPAID_ORDERS_WITH_PAYMENT']));
console.log('');
console.log('===== 💸 REFUNDS: RM ' + @json($totalRefunds) + ' =====');
</script>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-bold text-green-600">📦 ORDERS</h2>
        <p>Paid: {{ $paidOrders->count ?? 0 }} = RM {{ number_format($paidOrders->total ?? 0, 2) }}</p>
        <p>Pending: {{ $pendingOrders->count ?? 0 }} = RM {{ number_format($pendingOrders->total ?? 0, 2) }}</p>
        <p>Cancelled: {{ $cancelledOrders->count ?? 0 }} = RM {{ number_format($cancelledOrders->total ?? 0, 2) }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-bold text-blue-600">💳 PAYMENTS (Succeeded)</h2>
        <p>Total: {{ $succeededPayments->count ?? 0 }} = RM {{ number_format($succeededPayments->total ?? 0, 2) }}</p>
        <p class="text-red-500">Refunds: RM {{ number_format($totalRefunds, 2) }}</p>
        <p class="font-bold">Net: RM {{ number_format(($succeededPayments->total ?? 0) - $totalRefunds, 2) }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-bold text-purple-600">🎫 TICKETS (Paid Orders)</h2>
        <p>Count: {{ $ticketsFromPaidOrders->count ?? 0 }}</p>
        <p>Total: RM {{ number_format($ticketsFromPaidOrders->total ?? 0, 2) }}</p>
    </div>
</div>

<!-- Tickets by Type -->
<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="font-bold text-purple-600 mb-2">🎫 TICKETS BY TYPE (From Paid Orders)</h2>
    <table class="w-full text-sm">
        <thead><tr class="bg-purple-100"><th class="text-left p-2">Ticket Type</th><th class="text-right p-2">Capacity</th><th class="text-right p-2">Sold</th><th class="text-right p-2">Revenue</th></tr></thead>
        <tbody>
        @foreach($ticketsByType as $t)
        <tr class="border-b">
            <td class="p-2">{{ $t->ticket_name }}</td>
            <td class="text-right p-2">{{ $t->total_seats }}</td>
            <td class="text-right p-2 {{ $t->sold_count > $t->total_seats ? 'text-red-600 font-bold' : '' }}">{{ $t->sold_count }}</td>
            <td class="text-right p-2">RM {{ number_format($t->total_revenue, 2) }}</td>
        </tr>
        @endforeach
        <tr class="bg-purple-50 font-bold">
            <td class="p-2">TOTAL</td>
            <td class="text-right p-2">{{ $ticketsByType->sum('total_seats') }}</td>
            <td class="text-right p-2">{{ $ticketsByType->sum('sold_count') }}</td>
            <td class="text-right p-2">RM {{ number_format($ticketsByType->sum('total_revenue'), 2) }}</td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Tickets by Event Day -->
<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="font-bold text-indigo-600 mb-2">📅 TICKETS BY EVENT DAY (From Paid Orders)</h2>
    <table class="w-full text-sm">
        <thead><tr class="bg-indigo-100"><th class="text-left p-2">Event Day</th><th class="text-right p-2">Count</th><th class="text-right p-2">Revenue</th></tr></thead>
        <tbody>
        @foreach($ticketsByDay as $d)
        <tr class="border-b">
            <td class="p-2">{{ $d->event_day_name ?? 'N/A' }}</td>
            <td class="text-right p-2">{{ $d->count }}</td>
            <td class="text-right p-2">RM {{ number_format($d->total, 2) }}</td>
        </tr>
        @endforeach
        <tr class="bg-indigo-50 font-bold">
            <td class="p-2">TOTAL</td>
            <td class="text-right p-2">{{ $ticketsByDay->sum('count') }}</td>
            <td class="text-right p-2">RM {{ number_format($ticketsByDay->sum('total'), 2) }}</td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Tickets by Status (All) -->
<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="font-bold text-gray-600 mb-2">📊 ALL TICKETS BY STATUS</h2>
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-100"><th class="text-left p-2">Status</th><th class="text-right p-2">Count</th><th class="text-right p-2">Total Value</th></tr></thead>
        <tbody>
        @foreach($ticketsByStatus as $status => $t)
        <tr class="border-b">
            <td class="p-2">{{ $status }}</td>
            <td class="text-right p-2">{{ $t->count }}</td>
            <td class="text-right p-2">RM {{ number_format($t->total, 2) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="bg-red-50 p-4 rounded shadow mb-4">
    <h2 class="font-bold text-red-600">⚠️ PAID ORDERS WITHOUT PAYMENT ({{ $paidOrdersNoPayment->count() }}) = RM {{ number_format($paidOrdersNoPayment->sum('total_amount'), 2) }}</h2>
    <table class="w-full text-sm mt-2">
        <thead><tr class="bg-red-100">
            <th class="text-left p-2">Order #</th>
            <th class="text-left p-2">Order Status</th>
            <th class="text-left p-2">Ticket Type</th>
            <th class="text-left p-2">Ticket Status</th>
            <th class="text-center p-2">Qty</th>
            <th class="text-right p-2">Amount</th>
            <th class="text-left p-2">Method</th>
            <th class="text-left p-2">Date</th>
        </tr></thead>
        <tbody>
        @foreach($paidOrdersNoPayment as $o)
        <tr class="border-b">
            <td class="p-2">{{ $o->order_number }}</td>
            <td class="p-2"><span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">{{ $o->status }}</span></td>
            <td class="p-2">{{ $o->ticket_types }}</td>
            <td class="p-2"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $o->ticket_statuses }}</span></td>
            <td class="text-center p-2">{{ $o->ticket_count }}</td>
            <td class="text-right p-2">RM {{ number_format($o->total_amount, 2) }}</td>
            <td class="p-2">{{ $o->payment_method }}</td>
            <td class="p-2">{{ $o->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if($unpaidOrdersWithPayment->count() > 0)
<div class="bg-orange-50 p-4 rounded shadow">
    <h2 class="font-bold text-orange-600">⚠️ UNPAID ORDERS WITH PAYMENT ({{ $unpaidOrdersWithPayment->count() }})</h2>
    <table class="w-full text-sm mt-2">
        <thead><tr class="bg-orange-100"><th>Order #</th><th>Status</th><th>Amount</th></tr></thead>
        <tbody>
        @foreach($unpaidOrdersWithPayment as $o)
        <tr class="border-b"><td>{{ $o->order_number }}</td><td>{{ $o->status }}</td><td>RM {{ number_format($o->total_amount, 2) }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

</body>
</html>

