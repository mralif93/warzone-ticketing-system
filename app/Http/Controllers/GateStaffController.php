<?php

namespace App\Http\Controllers;

use App\Models\AdmittanceLog;
use App\Models\Event;
use App\Services\TicketValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GateStaffController extends Controller
{
    protected $ticketValidationService;

    public function __construct(TicketValidationService $ticketValidationService)
    {
        $this->middleware('auth');
        $this->middleware('gate.staff');
        $this->ticketValidationService = $ticketValidationService;
    }

    /**
     * Show the gate staff dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get today's events
        $todayEvents = Event::whereDate('event_date', today())
            ->where('status', 'Active')
            ->orderBy('event_date')
            ->get();

        // Get recent scans for this staff member
        $recentScans = AdmittanceLog::where('staff_user_id', $user->id)
            ->with('ticket.event')
            ->orderBy('scan_time', 'desc')
            ->limit(10)
            ->get();

        // Get scan statistics for today
        $todayStats = $this->getTodayStats($user->id);

        return view('gate-staff.dashboard', compact('todayEvents', 'recentScans', 'todayStats'));
    }

    /**
     * Show the QR code scanner interface
     */
    public function scanner(Request $request)
    {
        $eventId = $request->get('event_id');
        $gateId = $request->get('gate_id', 'GATE-1');
        
        $event = null;
        if ($eventId) {
            $event = Event::find($eventId);
        }

        // Get available events for today
        $todayEvents = Event::whereDate('event_date', today())
            ->where('status', 'Active')
            ->orderBy('event_date')
            ->get();

        return view('gate-staff.scanner', compact('event', 'todayEvents', 'gateId'));
    }

    /**
     * Process QR code scan
     */
    public function scanTicket(Request $request)
    {
        $request->validate([
            'qrcode' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'gate_id' => 'required|string',
        ]);

        $user = Auth::user();
        $qrcode = $request->qrcode;
        $eventId = $request->event_id;
        $gateId = $request->gate_id;

        try {
            // Validate the ticket
            $result = $this->ticketValidationService->validateTicket($qrcode, $gateId, $user->id);

            // Add event validation
            if ($result['status'] === 'SUCCESS' && $result['ticket']) {
                if ($result['ticket']->event_id != $eventId) {
                    $result = [
                        'status' => 'WRONG_EVENT',
                        'message' => 'Ticket is for a different event',
                        'ticket' => $result['ticket'],
                        'performance_time' => $result['performance_time'] ?? 0
                    ];
                }
            }

            // Log the scan attempt
            Log::info('Gate staff scan attempt', [
                'staff_user_id' => $user->id,
                'gate_id' => $gateId,
                'event_id' => $eventId,
                'qrcode' => $qrcode,
                'result' => $result['status']
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Gate staff scan error', [
                'staff_user_id' => $user->id,
                'gate_id' => $gateId,
                'event_id' => $eventId,
                'qrcode' => $qrcode,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'ERROR',
                'message' => 'An error occurred during scanning',
                'performance_time' => 0
            ], 500);
        }
    }

    /**
     * Show scan history for gate staff
     */
    public function scanHistory(Request $request)
    {
        $user = Auth::user();
        
        $query = AdmittanceLog::where('staff_user_id', $user->id)
            ->with(['ticket.event', 'ticket.seat']);

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('scan_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scan_time', '<=', $request->date_to);
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('scan_result', $request->result);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->whereHas('ticket', function($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        $scans = $query->orderBy('scan_time', 'desc')
            ->paginate(20);

        $events = Event::whereHas('tickets')
            ->orderBy('event_date', 'desc')
            ->get();

        $results = ['SUCCESS', 'DUPLICATE', 'INVALID', 'WRONG_GATE', 'WRONG_EVENT', 'ERROR'];

        return view('gate-staff.scan-history', compact('scans', 'events', 'results'));
    }

    /**
     * Get today's scan statistics
     */
    private function getTodayStats($staffUserId)
    {
        $today = today();
        
        $totalScans = AdmittanceLog::where('staff_user_id', $staffUserId)
            ->whereDate('scan_time', $today)
            ->count();

        $successfulScans = AdmittanceLog::where('staff_user_id', $staffUserId)
            ->whereDate('scan_time', $today)
            ->where('scan_result', 'SUCCESS')
            ->count();

        $duplicateScans = AdmittanceLog::where('staff_user_id', $staffUserId)
            ->whereDate('scan_time', $today)
            ->where('scan_result', 'DUPLICATE')
            ->count();

        $invalidScans = AdmittanceLog::where('staff_user_id', $staffUserId)
            ->whereDate('scan_time', $today)
            ->whereIn('scan_result', ['INVALID', 'WRONG_GATE', 'WRONG_EVENT'])
            ->count();

        return [
            'total_scans' => $totalScans,
            'successful_scans' => $successfulScans,
            'duplicate_scans' => $duplicateScans,
            'invalid_scans' => $invalidScans,
            'success_rate' => $totalScans > 0 ? round(($successfulScans / $totalScans) * 100, 1) : 0
        ];
    }
}
