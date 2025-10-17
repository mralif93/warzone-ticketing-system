<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\CustomerTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get recent tickets
        $recentTickets = CustomerTicket::whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['event'])
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming events
        $upcomingEvents = Event::where('status', 'On Sale')
            ->where('date_time', '>', now())
            ->orderBy('date_time')
            ->take(3)
            ->get();

        // Get order statistics
        $orderStats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_tickets' => CustomerTicket::whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'total_spent' => Order::where('user_id', $user->id)->sum('total_amount'),
            'upcoming_events' => $recentTickets->where('event.date_time', '>', now())->count()
        ];

        return view('customer.dashboard', compact('user', 'recentTickets', 'upcomingEvents', 'orderStats'));
    }

    /**
     * Show customer profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only([
            'name', 'email', 'phone_number', 'address', 'city', 'state', 'postal_code', 'country'
        ]));

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Show customer events
     */
    public function events(Request $request)
    {
        $query = Event::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_time', '<=', $request->date_to . ' 23:59:59');
        }

        $events = $query->withCount('customerTickets')->latest()->paginate(12);
        $statuses = Event::select('status')->distinct()->pluck('status');

        return view('customer.events', compact('events', 'statuses'));
    }

    /**
     * Show customer tickets
     */
    public function tickets(Request $request)
    {
        $user = Auth::user();
        
        $query = CustomerTicket::whereHas('order', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('qrcode', 'like', "%{$search}%")
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event_id', $request->event);
        }

        $tickets = $query->with(['event', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statuses = CustomerTicket::select('status')->distinct()->pluck('status');
        $events = Event::whereHas('customerTickets', function($q) use ($user) {
            $q->whereHas('order', function($orderQuery) use ($user) {
                $orderQuery->where('user_id', $user->id);
            });
        })->get();

        return view('customer.tickets', compact('tickets', 'statuses', 'events'));
    }

    /**
     * Show customer orders
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
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

        $orders = $query->with(['tickets.event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statuses = Order::select('status')->distinct()->pluck('status');

        return view('customer.orders', compact('orders', 'statuses'));
    }

    /**
     * Show customer support
     */
    public function support()
    {
        return view('customer.support');
    }

    /**
     * Show individual ticket details
     */
    public function showTicket(Ticket $ticket)
    {
        if ($ticket->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $ticket->load(['event', 'order.user']);
        
        return view('customer.ticket-details', compact('ticket'));
    }

    /**
     * Show individual order details
     */
    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['tickets.event', 'user']);
        
        return view('customer.order-details', compact('order'));
    }
}
