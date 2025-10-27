<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\PurchaseTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CounterStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('counter.staff');
    }

    /**
     * Show the counter staff dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get today's events
        $todayEvents = Event::whereDate('date_time', today())
            ->whereIn('status', ['on_sale', 'active'])
            ->orderBy('date_time')
            ->get();
        
        // Get recent ticket sales
        $recentTickets = PurchaseTicket::with(['event', 'order', 'ticketType'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get today's statistics
        $todayStats = [
            'tickets_sold' => PurchaseTicket::whereDate('created_at', today())->count(),
            'orders_processed' => Order::whereDate('created_at', today())->where('status', 'paid')->count(),
            'revenue' => Order::where('status', 'paid')->whereDate('created_at', today())->sum('total_amount'),
            'active_events' => Event::whereDate('date_time', today())->where('status', 'active')->count(),
        ];
        
        return view('counter-staff.dashboard', compact('user', 'todayEvents', 'recentTickets', 'todayStats'));
    }

    /**
     * Show scanner for ticket validation
     */
    public function scanner(Request $request)
    {
        $eventId = $request->get('event_id');
        $event = null;
        
        if ($eventId) {
            $event = Event::find($eventId);
        }
        
        // Get today's events
        $todayEvents = Event::whereDate('date_time', today())
            ->whereIn('status', ['on_sale', 'active'])
            ->orderBy('date_time')
            ->get();
        
        return view('counter-staff.scanner', compact('event', 'todayEvents'));
    }
}

