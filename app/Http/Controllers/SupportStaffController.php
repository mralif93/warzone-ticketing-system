<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\PurchaseTicket;
use App\Models\User;
use App\Models\AdmittanceLog;
use App\Models\Setting;
use App\Services\TicketValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupportStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('support.staff');
    }

    /**
     * Show the support staff dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get recent orders needing attention
        $recentOrders = Order::with(['event', 'user'])
            ->where('status', 'pending')
            ->orWhere('status', 'pending_payment')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get today's statistics
        $todayStats = [
            'total_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'paid')->whereDate('created_at', today())->sum('total_amount'),
            'active_users' => User::where('role', 'customer')->count(),
        ];
        
        // Get upcoming events
        $upcomingEvents = Event::where('status', 'on_sale')
            ->where('date_time', '>', now())
            ->orderBy('date_time')
            ->take(5)
            ->get();
        
        return view('support-staff.dashboard', compact('user', 'recentOrders', 'todayStats', 'upcomingEvents'));
    }

    /**
     * Show order details
     */
    public function showOrder(Order $order)
    {
        $order->load([
            'tickets.ticketType',
            'tickets.event',
            'tickets.order',
            'event',
            'user'
        ]);

        // Get system settings for pricing breakdown
        $serviceFeePercentage = Setting::get('service_fee_percentage', 0.0);
        $taxPercentage = Setting::get('tax_percentage', 0.0);

        return view('support-staff.order-details', compact('order', 'serviceFeePercentage', 'taxPercentage'));
    }

    /**
     * Show QR scanner interface
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
        
        return view('support-staff.scanner', compact('event', 'todayEvents'));
    }

    /**
     * Search tickets by QR code or ticket ID
     */
    public function searchTicket(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $searchTerm = $request->search;
        
        // Search by QR code or ticket ID
        $ticket = PurchaseTicket::where('qrcode', $searchTerm)
            ->orWhere('id', $searchTerm)
            ->with(['event', 'order.user', 'ticketType'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        // Get ticket scan history
        $scanHistory = AdmittanceLog::where('ticket_id', $ticket->id)
            ->orderBy('scan_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'ticket' => $ticket,
            'scanHistory' => $scanHistory
        ]);
    }

    /**
     * Scan ticket for attendance validation
     */
    public function scanTicket(Request $request, TicketValidationService $validationService)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'event_id' => 'required|integer|exists:events,id',
            'gate_id' => 'nullable|string',
        ]);

        try {
            // Find ticket by QR code
            $ticket = PurchaseTicket::where('qrcode', $request->qr_code)->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found with this QR code',
                    'scan_result' => 'INVALID'
                ]);
            }

            // Validate the ticket
            $result = $validationService->validateTicket(
                $ticket->id,
                $request->event_id,
                $request->gate_id ?? 'SUPPORT-1'
            );

            // Create admittance log for support staff
            $admittanceLog = AdmittanceLog::create([
                'ticket_id' => $ticket->id,
                'event_id' => $request->event_id,
                'gate_id' => $request->gate_id ?? 'SUPPORT-1',
                'staff_user_id' => Auth::id(),
                'scan_result' => $result['result'],
                'scan_time' => now(),
                'notes' => 'Scanned by Support Staff',
            ]);

            // Also log to audit trail
            $gateId = $request->gate_id ?? 'SUPPORT-1';
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'scan',
                'table_name' => 'purchase_tickets',
                'record_id' => $ticket->id,
                'old_values' => ['status' => $ticket->status],
                'new_values' => ['status' => $ticket->status, 'scanned_at' => now()],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'description' => "Support staff scanned ticket: {$result['result']} at {$gateId}"
            ]);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'scan_result' => $result['result'],
                'ticket' => $ticket->load(['event', 'order.user', 'ticketType'])
            ]);

        } catch (\Exception $e) {
            Log::error('Support staff ticket scan error', [
                'error' => $e->getMessage(),
                'qr_code' => $request->qr_code
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error scanning ticket: ' . $e->getMessage(),
                'scan_result' => 'ERROR'
            ], 500);
        }
    }

    /**
     * Handle ticket cancellation request
     */
    public function cancelTicket(Request $request, PurchaseTicket $ticket)
    {
        // Implementation for ticket cancellation
        return response()->json(['message' => 'Ticket cancellation initiated']);
    }

    /**
     * Handle refund request
     */
    public function processRefund(Request $request, Order $order)
    {
        // Implementation for refund processing
        return response()->json(['message' => 'Refund processing initiated']);
    }
}

