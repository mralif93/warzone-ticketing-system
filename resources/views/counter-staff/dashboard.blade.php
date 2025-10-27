@extends('layouts.app')

@section('title', 'Counter Staff Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('counter-staff.dashboard') }}" class="flex items-center">
                        <span class="text-xl sm:text-2xl font-bold text-red-600">WARZONE</span>
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('counter-staff.dashboard') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('counter-staff.dashboard') ? 'text-red-600' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('counter-staff.scanner') }}" 
                       class="text-gray-600 hover:text-red-600 px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('counter-staff.scanner') ? 'text-red-600' : '' }}">
                        Ticket Scanner
                    </a>
                </div>

                <!-- User Menu - Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="ml-2 text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <i class='bx bx-chevron-down text-sm text-gray-500'></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">Counter Staff</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-100 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                        <i class='bx bx-log-out text-base mr-3'></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 p-2">
                        <i class='bx bx-menu text-2xl'></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-1" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-1" 
                 class="md:hidden absolute top-full left-0 right-0 bg-white border-t border-gray-200 shadow-md z-50">
                <div class="px-3 py-2 space-y-1">
                    <div class="px-3 py-2 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200 mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-red-600 font-medium">Counter Staff</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group">
                                <div class="flex items-center justify-center w-8 h-8 rounded-md bg-gray-100 group-hover:bg-red-100 mr-3 transition-colors duration-200">
                                    <i class="bx bx-log-out text-sm text-gray-600 group-hover:text-red-600 transition-colors duration-200"></i>
                                </div>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Counter Staff Dashboard</h1>
                    <p class="text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Statistics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-purchase-tag text-blue-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Tickets Sold</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['tickets_sold'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-check-circle text-green-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Orders</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['orders_processed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-money text-yellow-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Revenue</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">RM{{ number_format($todayStats['revenue'], 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="bx bx-calendar text-purple-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Active Events</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $todayStats['active_events'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <a href="{{ route('counter-staff.scanner') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        <i class="bx bx-qr-scan text-sm mr-2"></i>
                        Open Ticket Scanner
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Today's Events -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Today's Events</h3>
                </div>
                <div class="p-4 sm:p-6">
                    @if($todayEvents->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayEvents as $event)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 rounded-lg space-y-3 sm:space-y-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate">{{ $event->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="bx bx-time mr-1"></i>
                                            {{ $event->date_time->format('g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="bx bx-calendar-x text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">No events scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Ticket Sales -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Ticket Sales</h3>
                </div>
                <div class="p-4 sm:p-6">
                    @if($recentTickets->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTickets as $ticket)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $ticket->event->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $ticket->ticketType->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">RM{{ number_format($ticket->price_paid, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="bx bx-purchase-tag text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">No ticket sales yet today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

