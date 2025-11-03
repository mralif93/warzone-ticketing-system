<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\PurchaseTicket;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Get database-agnostic date format for grouping
     */
    private function getDateGroupFormat($column = 'created_at', $format = 'Y-m')
    {
        $driver = config('database.default');
        
        // Get the actual driver name from the connection config
        $driverName = config("database.connections.{$driver}.driver");
        
        if ($driverName === 'sqlite') {
            return DB::raw("strftime('%Y-%m', {$column}) as date_group");
        } elseif ($driverName === 'pgsql') {
            return DB::raw("TO_CHAR({$column}, 'YYYY-MM') as date_group");
        } else {
            // MySQL or other databases that support DATE_FORMAT
            return DB::raw("DATE_FORMAT({$column}, '%Y-%m') as date_group");
        }
    }

    /**
     * Get database-agnostic date format for daily grouping
     */
    private function getDailyDateGroupFormat($column = 'created_at')
    {
        $driver = config('database.default');
        
        // Get the actual driver name from the connection config
        $driverName = config("database.connections.{$driver}.driver");
        
        if ($driverName === 'sqlite') {
            return DB::raw("date({$column}) as date_group");
        } elseif ($driverName === 'pgsql') {
            return DB::raw("DATE({$column}) as date_group");
        } else {
            return DB::raw("DATE({$column}) as date_group");
        }
    }

    /**
     * Get revenue data using Laravel's collection methods (database agnostic)
     */
    private function getRevenueData($dateFrom, $dateTo)
    {
        return Order::where('status', 'paid')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function($orders, $date) {
                return [
                    'date' => $date,
                    'total' => $orders->sum('total_amount')
                ];
            })
            ->values();
    }

    /**
     * Get user registration data using Laravel's collection methods (database agnostic)
     */
    private function getUserRegistrationData($dateFrom, $dateTo)
    {
        return User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
            ->groupBy(function($user) {
                return $user->created_at->format('Y-m-d');
            })
            ->map(function($users, $date) {
                return [
                    'date' => $date,
                    'count' => $users->count()
                ];
            })
            ->values();
    }
    /**
     * Show admin dashboard with comprehensive statistics
     */
    public function dashboard()
    {
        // Get comprehensive statistics
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'events_on_sale' => Event::where('status', 'on_sale')->count(),
            'events_draft' => Event::where('status', 'draft')->count(),
            'events_sold_out' => Event::where('status', 'sold_out')->count(),
            'total_orders' => Order::count(),
            'total_tickets_sold' => PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])->count(),
            'total_tickets_held' => PurchaseTicket::where('status', 'held')->count(),
            'total_revenue' => Order::where('status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'paid')->count(),
            'total_tickets' => PurchaseTicket::count(),
            'sold_tickets' => PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])->count(),
        ];

        // Get recent activity
        $recentOrders = Order::with(['user', 'tickets'])
            ->latest()
            ->limit(5)
            ->get();

        $recentTickets = PurchaseTicket::with(['order.user', 'event'])
            ->latest()
            ->limit(10)
            ->get();

        $recentAuditLogs = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Get user role distribution
        $userRoles = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        // Get event status distribution
        $eventStatuses = Event::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Get revenue by month (last 6 months) - Database agnostic
        $revenueByMonth = Order::select(
                $this->getDateGroupFormat('created_at'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();

        $user = Auth::user();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentTickets',
            'recentAuditLogs',
            'userRoles',
            'eventStatuses',
            'revenueByMonth',
            'user'
        ));
    }

    /**
     * Show user management page
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount(['orders', 'purchaseTickets'])
            ->paginate(15);

        $roles = User::select('role')->distinct()->pluck('role');

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->loadCount(['orders', 'purchaseTickets']);
        $user->load(['orders.purchaseTickets', 'auditLogs']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrator,gate_staff,counter_staff,support_staff,customer',
            'phone_number' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'country' => $request->country,
            'email_verified_at' => now(),
        ]);

        // Log the user creation
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => null,
            'new_values' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "User created: {$user->name} ({$user->email}) with role {$user->role}"
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:administrator,gate_staff,counter_staff,support_staff,customer',
            'phone_number' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $oldValues = AuditLog::sanitizeValues($user->toArray(), 'users');

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'country' => $request->country,
        ]);

        $newValues = AuditLog::sanitizeValues($user->toArray(), 'users');

        // Log the user update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "User updated: {$user->name} ({$user->email})"
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting the last admin
        if ($user->role === 'Administrator' && User::where('role', 'Administrator')->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last administrator.']);
        }

        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete user with existing orders.']);
        }

        $oldValues = $user->toArray();
        $userName = $user->name;

        $user->delete();

        // Log the user deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "User deleted: {$user->name} ({$user->email}) with role {$user->role}"
        ]);

        return redirect()->route('admin.users')
            ->with('success', "User '{$userName}' deleted successfully!");
    }

    /**
     * Show orders management page
     */
    public function orders(Request $request)
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

        $orders = $query->latest()->paginate(15);
        $statuses = Order::select('status')->distinct()->pluck('status');

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show order details
     */
    public function showOrder(Order $order)
    {
        $order->load(['user', 'purchaseTickets.event', 'payments']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show tickets management page
     */
    public function tickets(Request $request)
    {
        $query = PurchaseTicket::with(['order.user', 'event']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('qrcode', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15);
        $statuses = PurchaseTicket::select('status')->distinct()->pluck('status');

        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    /**
     * Show ticket details
     */
    public function showTicket(PurchaseTicket $ticket)
    {
        $ticket->load(['order.user', 'event', 'admittanceLogs.staffUser']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show audit logs
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('table_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $auditLogs = $query->latest()->paginate(20);
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit-logs.index', compact('auditLogs', 'actions'));
    }

    /**
     * Show reports page
     */
    public function reports(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Revenue report - Database agnostic using collections
        $revenueData = $this->getRevenueData($dateFrom, $dateTo);
        $totalRevenue = $revenueData->sum('total');

        // Ticket sales by event
        $ticketSalesByEvent = PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('event')
            ->get()
            ->groupBy('event.name')
            ->map(function($tickets, $eventName) {
                $event = $tickets->first()->event;
                return (object)[
                    'title' => $eventName,
                    'event_date' => $event->date_time,
                    'count' => $tickets->count(),
                    'revenue' => $tickets->sum('price_paid')
                ];
            })->values();

        // User registration report - Database agnostic using collections
        $userRegistrations = $this->getUserRegistrationData($dateFrom, $dateTo);

        // User distribution by role
        $usersByRole = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->orderBy('count', 'desc')
            ->get();

        // Top customers by spending
        $topCustomers = User::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->get()
            ->filter(function($user) {
                return $user->orders_sum_total_amount > 0;
            })
            ->sortByDesc('orders_sum_total_amount')
            ->take(10);

        return view('admin.reports.index', compact(
            'revenueData',
            'ticketSalesByEvent',
            'userRegistrations',
            'topCustomers',
            'usersByRole',
            'totalRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    // Event Management
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

        $events = $query->withCount('purchaseTickets')->latest()->paginate(15);
        $statuses = Event::select('status')->distinct()->pluck('status');

        return view('admin.events.index', compact('events', 'statuses'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
        ]);

        Event::create($request->all());

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function showEvent(Event $event)
    {
        $event->loadCount('purchaseTickets');
        $recentTickets = $event->purchaseTickets()->with('order.user')->latest()->take(10)->get();
        
        $ticketStats = [
            'total_capacity' => 7000,
            'tickets_sold' => $event->purchaseTickets()->where('status', 'active')->count(),
            'tickets_held' => $event->purchaseTickets()->where('status', 'held')->count(),
            'tickets_available' => 7000 - $event->purchaseTickets()->whereIn('status', ['active', 'held'])->count(),
        ];
        
        $ticketStats['sold_percentage'] = $ticketStats['total_capacity'] > 0 
            ? round(($ticketStats['tickets_sold'] / $ticketStats['total_capacity']) * 100, 2) 
            : 0;

        return view('admin.events.show', compact('event', 'recentTickets', 'ticketStats'));
    }

    public function editEvent(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
        ]);

        $event->update($request->all());

        return redirect()->route('admin.events.show', $event)->with('success', 'Event updated successfully.');
    }

    public function deleteEvent(Event $event)
    {
        // Check if event has tickets
        if ($event->purchaseTickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete event with existing tickets.']);
        }

        $eventName = $event->name;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$eventName}' deleted successfully!");
    }

    public function changeEventStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:draft,on_sale,sold_out,cancelled'
        ]);

        $event->update(['status' => $request->status]);

        return back()->with('success', 'Event status updated successfully.');
    }

    /**
     * Show system settings
     */
    public function settings()
    {
        // Get settings from database
        $settings = Setting::getAll();

        // Initialize default settings if they don't exist
        $defaults = [
            'max_tickets_per_order' => '10',
            'seat_hold_duration_minutes' => '15',
            'maintenance_mode' => '0',
            'auto_release_holds' => '1',
            'email_notifications' => '1',
            'admin_email' => '',
            'session_timeout' => '60',
            'max_login_attempts' => '5',
            'service_fee_percentage' => '0.0',
            'tax_percentage' => '0.0',
        ];

        foreach ($defaults as $key => $defaultValue) {
            if (!isset($settings[$key])) {
                // Determine the type based on the setting key
                $type = 'string';
                if (in_array($key, ['max_tickets_per_order', 'seat_hold_duration_minutes', 'session_timeout', 'max_login_attempts'])) {
                    $type = 'integer';
                } elseif (in_array($key, ['maintenance_mode', 'auto_release_holds', 'email_notifications'])) {
                    $type = 'boolean';
                } elseif (in_array($key, ['service_fee_percentage', 'tax_percentage'])) {
                    $type = 'string';
                }
                
                Setting::set($key, $defaultValue, $type);
                $settings[$key] = $defaultValue;
            }
        }
        
        // Ensure all boolean values are returned as strings for the view
        $booleanKeys = ['maintenance_mode', 'auto_release_holds', 'email_notifications'];
        foreach ($booleanKeys as $key) {
            if (isset($settings[$key])) {
                // Convert boolean values to string '0' or '1'
                $settings[$key] = $settings[$key] ? '1' : '0';
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'max_tickets_per_order' => 'required|integer|min:1|max:20',
            'seat_hold_duration_minutes' => 'required|integer|min:1|max:30',
            'maintenance_mode' => 'required|in:0,1',
            'auto_release_holds' => 'required|in:0,1',
            'email_notifications' => 'required|in:0,1',
            'admin_email' => 'nullable|email|max:255',
            'session_timeout' => 'required|integer|min:15|max:480',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'service_fee_percentage' => 'required|numeric|min:0|max:100',
            'tax_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Get old settings for audit
        $oldSettings = Setting::getAll();
        
        // Update settings in database
        Setting::set('max_tickets_per_order', $request->max_tickets_per_order, 'integer');
        Setting::set('seat_hold_duration_minutes', $request->seat_hold_duration_minutes, 'integer');
        Setting::set('maintenance_mode', $request->maintenance_mode, 'boolean');
        Setting::set('auto_release_holds', $request->auto_release_holds, 'boolean');
        Setting::set('email_notifications', $request->email_notifications, 'boolean');
        
        // Only update admin_email if provided
        if ($request->admin_email) {
            Setting::set('admin_email', $request->admin_email, 'string');
        }
        
        Setting::set('session_timeout', $request->session_timeout, 'integer');
        Setting::set('max_login_attempts', $request->max_login_attempts, 'integer');
        Setting::set('service_fee_percentage', $request->service_fee_percentage, 'string');
        Setting::set('tax_percentage', $request->tax_percentage, 'string');
        
        // Get new settings for audit
        $newSettings = Setting::getAll();
        
        // Log settings update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'settings',
            'record_id' => 0, // Settings table doesn't have a single record ID
            'old_values' => $oldSettings,
            'new_values' => $newSettings,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => 'System settings updated'
        ]);

        return back()->with('success', 'Settings updated successfully!');
    }
}