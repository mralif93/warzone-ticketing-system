@extends('layouts.app')

@section('title', 'Scan History')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('gate-staff.dashboard') }}" 
                       class="flex items-center text-gray-600 hover:text-wwc-primary transition-colors">
                        <i class="bx bx-arrow-back text-xl mr-2"></i>
                        <span class="font-medium">Back to Dashboard</span>
                    </a>
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-bold text-gray-900">Scan History</h1>
                    <p class="text-sm text-gray-600">View all your ticket scans</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                            From Date
                        </label>
                        <input type="date" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                            To Date
                        </label>
                        <input type="date" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                    </div>
                    <div>
                        <label for="result" class="block text-sm font-medium text-gray-700 mb-2">
                            Result
                        </label>
                        <select id="result" name="result" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                            <option value="">All Results</option>
                            @foreach($results as $result)
                                <option value="{{ $result }}" {{ request('result') == $result ? 'selected' : '' }}>
                                    {{ $result }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Event
                        </label>
                        <select id="event_id" name="event_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-wwc-primary focus:border-wwc-primary">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4 flex justify-end space-x-4">
                        <a href="{{ route('gate-staff.scan-history') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md font-medium transition-colors">
                            Clear Filters
                        </a>
                        <button type="submit" 
                                class="bg-wwc-primary hover:bg-wwc-primary-dark text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scan History Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Scan History</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $scans->total() }} total scans</p>
            </div>
            
            @if($scans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Time
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Event
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ticket Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Result
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gate
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($scans as $scan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $scan->scan_time->format('M j, Y') }}</div>
                                            <div class="text-gray-500">{{ $scan->scan_time->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $scan->ticket->event->name ?? 'Unknown Event' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($scan->ticket)
                                            <div>
                                                <div class="font-medium">Zone: {{ $scan->ticket->seat_price_zone ?? 'N/A' }}</div>
                                                <div class="text-gray-500">
                                                    Seat: {{ $scan->ticket->seat_section ?? '' }}{{ $scan->ticket->seat_row ?? '' }}-{{ $scan->ticket->seat_number ?? '' }}
                                                </div>
                                                <div class="text-gray-500">
                                                    Price: ${{ number_format($scan->ticket->price_paid ?? 0, 2) }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">No ticket data</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($scan->scan_result === 'SUCCESS') bg-green-100 text-green-800
                                            @elseif($scan->scan_result === 'DUPLICATE') bg-red-100 text-red-800
                                            @elseif($scan->scan_result === 'INVALID') bg-red-100 text-red-800
                                            @elseif($scan->scan_result === 'WRONG_GATE') bg-yellow-100 text-yellow-800
                                            @elseif($scan->scan_result === 'WRONG_EVENT') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($scan->scan_result === 'SUCCESS')
                                                <i class="bx bx-check-circle mr-1"></i>
                                            @elseif($scan->scan_result === 'DUPLICATE')
                                                <i class="bx bx-x-circle mr-1"></i>
                                            @elseif($scan->scan_result === 'INVALID')
                                                <i class="bx bx-error mr-1"></i>
                                            @else
                                                <i class="bx bx-error-circle mr-1"></i>
                                            @endif
                                            {{ $scan->scan_result }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $scan->gate_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="showScanDetails({{ $scan->id }})" 
                                                class="text-wwc-primary hover:text-wwc-primary-dark">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $scans->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="bx bx-qr-scan text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No scans found</h3>
                    <p class="text-gray-500">No scans match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scan Details Modal -->
<div id="scan-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Scan Details</h3>
                <button onclick="closeScanDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>
            <div id="scan-details-content" class="space-y-3">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showScanDetails(scanId) {
    // This would typically make an AJAX call to get scan details
    // For now, we'll show a placeholder
    document.getElementById('scan-details-content').innerHTML = `
        <div class="text-center py-4">
            <p class="text-gray-500">Scan details for ID: ${scanId}</p>
            <p class="text-sm text-gray-400 mt-2">Detailed scan information would be loaded here.</p>
        </div>
    `;
    document.getElementById('scan-details-modal').classList.remove('hidden');
}

function closeScanDetails() {
    document.getElementById('scan-details-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('scan-details-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScanDetails();
    }
});
</script>
@endsection
