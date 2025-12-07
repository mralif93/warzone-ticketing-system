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

    // TICKETS
    $ticketsByStatus = \App\Models\PurchaseTicket::selectRaw('status, COUNT(*) as count, SUM(price_paid) as total')
        ->groupBy('status')->get()->keyBy('status');
    
    $ticketsFromPaidOrders = \App\Models\PurchaseTicket::whereHas('order', fn($q) => $q->where('status', 'paid'))
        ->selectRaw('COUNT(*) as count, SUM(price_paid) as total')->first();

    // DISCREPANCIES
    $paidOrdersNoPayment = \App\Models\Order::where('status', 'paid')
        ->whereDoesntHave('payments', fn($q) => $q->where('status', 'succeeded'))
        ->select('id', 'order_number', 'total_amount', 'payment_method', 'created_at')
        ->orderBy('created_at')->get();

    $unpaidOrdersWithPayment = \App\Models\Order::where('status', '!=', 'paid')
        ->whereHas('payments', fn($q) => $q->where('status', 'succeeded'))
        ->select('id', 'order_number', 'status', 'total_amount', 'created_at')->get();

    $totalRefunds = \App\Models\Payment::where('refund_amount', '>', 0)->sum('refund_amount');

    // For console
    $consoleData = [
        'ORDERS' => $ordersByStatus->map(fn($o) => ['count' => $o->count, 'total' => $o->total]),
        'PAYMENTS' => $paymentsByStatus->map(fn($p) => ['count' => $p->count, 'total' => $p->total]),
        'PAYMENTS_BY_METHOD' => $paymentsByMethod,
        'TICKETS' => $ticketsByStatus->map(fn($t) => ['count' => $t->count, 'total' => $t->total]),
        'TICKETS_FROM_PAID_ORDERS' => $ticketsFromPaidOrders,
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
console.log('===== 🎫 TICKETS BY STATUS =====');
console.table(@json($consoleData['TICKETS']));
console.log('');
console.log('===== ✅ TICKETS FROM PAID ORDERS =====');
console.log(@json($consoleData['TICKETS_FROM_PAID_ORDERS']));
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

<div class="bg-red-50 p-4 rounded shadow mb-4">
    <h2 class="font-bold text-red-600">⚠️ PAID ORDERS WITHOUT PAYMENT ({{ $paidOrdersNoPayment->count() }}) = RM {{ number_format($paidOrdersNoPayment->sum('total_amount'), 2) }}</h2>
    <table class="w-full text-sm mt-2">
        <thead><tr class="bg-red-100"><th>Order #</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
        <tbody>
        @foreach($paidOrdersNoPayment as $o)
        <tr class="border-b"><td>{{ $o->order_number }}</td><td>RM {{ number_format($o->total_amount, 2) }}</td><td>{{ $o->payment_method }}</td><td>{{ $o->created_at }}</td></tr>
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

