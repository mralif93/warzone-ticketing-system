@extends('layouts.admin')

@section('title', 'Order Management')
@section('page-subtitle', 'View and manage customer orders')

@section('content')
<!-- Professional Order Management with WWC Brand Design -->
<div class="min-h-screen bg-wwc-neutral-50">
    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="mx-auto">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->total() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Orders</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-info font-semibold">
                                    <i class='bx bx-receipt text-xs mr-1'></i>
                                    {{ $orders->where('status', 'paid')->count() }} Paid
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl text-blue-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Paid Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->where('status', 'paid')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Paid Orders</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-success font-semibold">
                                    <i class='bx bx-check text-xs mr-1'></i>
                                    {{ $orders->where('status', 'pending')->count() }} Pending
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">{{ $orders->where('status', 'pending')->count() }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Pending</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-error font-semibold">
                                    <i class='bx bx-x text-xs mr-1'></i>
                                    {{ $orders->where('status', 'cancelled')->count() }} Cancelled
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class='bx bx-time text-2xl text-red-600'></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-wwc-neutral-900 mb-1">RM{{ number_format($totalRevenue, 0) }}</div>
                            <div class="text-xs text-wwc-neutral-600 mb-2 font-medium">Total Revenue</div>
                            <div class="flex items-center">
                                <div class="flex items-center text-xs text-wwc-accent font-semibold">
                                    <i class='bx bx-trending-up text-xs mr-1'></i>
                                    RM{{ number_format($averageOrderValue, 0) }} Avg
                                </div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class='bx bx-dollar text-2xl text-orange-600'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 mb-6">
                <div class="px-6 py-4 border-b border-wwc-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-wwc-neutral-900">Search & Filter Orders</h3>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.orders.trashed') }}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200">
                                <i class='bx bx-trash text-xs mr-1'></i>
                                View Trashed
                            </a>
                            <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                <i class='bx bx-search text-sm'></i>
                                <span>Find specific orders</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Search Orders</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search by order number, customer name, or email..."
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">Status</label>
                            <select name="status" id="status" class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                   class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-lg focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-search text-sm mr-2'></i>
                                Search Orders
                            </button>
                            <a href="{{ route('admin.orders.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-wwc-neutral-300 shadow-sm text-sm font-semibold rounded-lg text-wwc-neutral-700 bg-white hover:bg-wwc-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-x text-sm mr-2'></i>
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section with Create Button -->
            <div class="flex justify-end items-center mb-6">
                <a href="{{ route('admin.orders.create') }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-plus text-sm mr-2'></i>
                    Create New Order
                </a>
            </div>

            <!-- Orders List -->
            @if($orders->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="px-6 py-4 border-b border-wwc-neutral-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-wwc-neutral-900">All Orders</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2 text-xs text-wwc-neutral-500">
                                    <i class='bx bx-receipt text-sm'></i>
                                    <span>Showing {{ $orders->count() }} of {{ $orders->total() }} orders</span>
                                </div>
                                <form method="GET" class="flex items-center space-x-2">
                                    @foreach(request()->except('limit') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <label for="limit" class="text-xs font-semibold text-wwc-neutral-600">Limit:</label>
                                    <select name="limit" id="limit" onchange="this.form.submit()" class="px-2 py-1 border border-wwc-neutral-300 rounded text-xs focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary">
                                        <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ request('limit', 10) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('limit', 10) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-wwc-neutral-100">
                        <thead class="bg-wwc-neutral-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Order
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                    Quantity
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-center">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-wwc-neutral-600 uppercase tracking-wider text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                            <tbody class="bg-white divide-y divide-wwc-neutral-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-wwc-neutral-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-wwc-primary-light flex items-center justify-center">
                                                    <i class='bx bx-receipt text-lg text-wwc-primary'></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                                <div class="text-sm font-semibold text-wwc-neutral-900">{{ $order->order_number }}</div>
                                                <div class="text-xs text-wwc-neutral-500">{{ $order->tickets->count() }} tickets</div>
                                                @if($order->qrcode)
                                                <div class="text-xs text-wwc-neutral-400 font-mono">QR: {{ $order->qrcode }}</div>
                                                @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-wwc-neutral-500">{{ $order->customer_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-wwc-neutral-900">RM{{ number_format($order->total_amount, 0) }}</div>
                                    <div class="text-xs text-wwc-neutral-500">total amount</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-semibold text-wwc-neutral-900">{{ $order->tickets->count() }}</div>
                                    <div class="text-xs text-wwc-neutral-500">tickets</div>
                                </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'refunded') bg-gray-100 text-gray-800
                                            @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucwords($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-wwc-neutral-900">{{ $order->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-wwc-neutral-500">{{ $order->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-wwc-primary hover:bg-wwc-primary-dark rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="View order details">
                                                <i class='bx bx-show text-xs mr-1.5'></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                               title="Edit order">
                                                <i class='bx bx-edit text-xs mr-1.5'></i>
                                            Edit
                                        </a>
                                            <div class="relative" x-data="{ open{{ $order->id }}: false }">
                                                <button @click="open{{ $order->id }} = !open{{ $order->id }}" 
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-wwc-neutral-700 bg-white border border-wwc-neutral-300 hover:bg-wwc-neutral-50 hover:border-wwc-neutral-400 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                        title="More actions">
                                                    <i class='bx bx-dots-vertical text-xs mr-1.5'></i>
                                                    More
                                                </button>
                                                <div x-show="open{{ $order->id }}" 
                                                     @click.away="open{{ $order->id }} = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-wwc-neutral-200 z-10"
                                                     style="display: none;">
                                                    <div class="py-1">
                                                        @if($order->status === 'pending')
                                                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="block">
                                                                @csrf
                                                                <input type="hidden" name="status" value="paid">
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-success hover:text-white transition-colors duration-200">
                                                                    <i class='bx bx-check text-xs mr-2'></i>
                                                                    Mark as Paid
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($order->status === 'paid')
                                                            <a href="{{ route('admin.orders.refund', $order) }}" 
                                                               class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-warning hover:text-white transition-colors duration-200"
                                                               onclick="return confirm('Are you sure you want to refund this order?')">
                                                                <i class='bx bx-undo text-xs mr-2'></i>
                                                                Refund Order
                                                            </a>
                                                        @endif
                                                        @if($order->status !== 'paid')
                                                            <a href="{{ route('admin.orders.cancel', $order) }}" 
                                                               class="flex items-center px-4 py-2 text-xs text-wwc-neutral-700 hover:bg-wwc-warning hover:text-white transition-colors duration-200"
                                                               onclick="return confirm('Are you sure you want to cancel this order?')">
                                                                <i class='bx bx-x text-xs mr-2'></i>
                                                                Cancel Order
                                                            </a>
                                                        @endif
                                                        <div class="border-t border-wwc-neutral-100 my-1"></div>
                                                        @if($order->status !== 'paid')
                                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')" 
                                                                  class="block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                    <i class='bx bx-trash text-xs mr-2'></i>
                                                                    Delete Order
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-wwc-neutral-100">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
                    <div class="text-center py-12">
                        <div class="mx-auto h-16 w-16 rounded-full bg-wwc-neutral-100 flex items-center justify-center mb-4">
                            <i class='bx bx-receipt text-3xl text-wwc-neutral-400'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-wwc-neutral-900 font-display mb-2">No orders found</h3>
                        <p class="text-sm text-wwc-neutral-600 mb-6">Get started by creating your first order to begin processing customer purchases.</p>
                        <div>
                            <a href="{{ route('admin.orders.create') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                                <i class='bx bx-plus text-sm mr-2'></i>
                                Create Your First Order
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function changeOrderStatus(orderId, newStatus) {
    if (!newStatus) return;
    
    if (confirm('Are you sure you want to change the order status?')) {
        fetch(`/admin/orders/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to change order status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to change order status');
        });
    }
}
</script>
@endsection