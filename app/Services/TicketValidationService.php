<?php

namespace App\Services;

use App\Models\PurchaseTicket;
use App\Models\AdmittanceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TicketValidationService
{
    /**
     * Validate ticket for admittance with sub-1.0 second performance
     * 
     * @param string $qrcode
     * @param string $gateId
     * @param int $staffUserId
     * @return array
     */
    public function validateTicket(string $qrcode, string $gateId, int $staffUserId): array
    {
        $startTime = microtime(true);
        
        try {
            // Use raw SQL for maximum performance
            $ticket = $this->getTicketForValidation($qrcode);
            
            if (!$ticket) {
                return $this->createValidationResult('invalid', 'Ticket not found', $gateId, $staffUserId, $startTime);
            }

            // Check if ticket is already scanned
            if ($ticket->status === 'scanned' || !is_null($ticket->scanned_at)) {
                return $this->createValidationResult('duplicate', 'Ticket already scanned', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Check if ticket is valid for scanning
            if (!in_array($ticket->status, ['sold', 'active', 'pending'])) {
                return $this->createValidationResult('invalid', 'Ticket not ready for scanning', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Check if ticket is for the correct event (if event is happening today)
            if (!$this->isValidForCurrentEvent($ticket)) {
                return $this->createValidationResult('wrong_gate', 'Ticket not valid for current event', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Mark ticket as scanned atomically
            $this->markTicketAsScanned($ticket, $gateId, $staffUserId);

            // Log successful admittance
            $this->logAdmittance($ticket, 'success', $gateId, $staffUserId);

            return $this->createValidationResult('success', 'Access granted', $gateId, $staffUserId, $startTime, $ticket);

        } catch (\Exception $e) {
            Log::error('Ticket validation failed', [
                'qrcode' => $qrcode,
                'gate_id' => $gateId,
                'staff_user_id' => $staffUserId,
                'error' => $e->getMessage()
            ]);

            return $this->createValidationResult('error', 'Validation error occurred', $gateId, $staffUserId, $startTime);
        }
    }

    /**
     * Get ticket for validation with optimized query
     */
    private function getTicketForValidation(string $qrcode): ?PurchaseTicket
    {
        // Trim and clean the QR code
        $qrcode = trim($qrcode);
        
        Log::info('Searching for ticket with QR code', [
            'qrcode' => $qrcode,
            'length' => strlen($qrcode)
        ]);
        
            // Use raw SQL for maximum performance with proper indexing
        $result = DB::selectOne("
            SELECT pt.id, pt.event_id, pt.qrcode, pt.status, pt.scanned_at, 
                   pt.price_paid, pt.created_at, pt.ticket_type_id, pt.zone, pt.event_day_name,
                   e.name as event_name, e.date_time as event_date, e.venue,
                   t.name as ticket_type_name
            FROM purchase pt
            LEFT JOIN events e ON pt.event_id = e.id
            LEFT JOIN tickets t ON pt.ticket_type_id = t.id
            WHERE pt.qrcode = ? 
            AND pt.deleted_at IS NULL
        ", [$qrcode]);
        
        if (!$result) {
            // Log for debugging
            $allTickets = DB::select("SELECT COUNT(*) as count FROM purchase WHERE qrcode = ?", [$qrcode]);
            $count = $allTickets[0]->count ?? 0;
            
            Log::warning('Ticket not found', [
                'qrcode' => $qrcode,
                'exists_in_db' => $count > 0
            ]);
            
            return null;
        }
        
        Log::info('Ticket found', [
            'id' => $result->id,
            'status' => $result->status,
            'event_name' => $result->event_name
        ]);

            // Convert to PurchaseTicket model for consistency
        $ticket = PurchaseTicket::find($result->id);
        if ($ticket) {
            // Add event and ticket type information to the ticket object
            $ticket->event_name = $result->event_name;
            $ticket->venue = $result->venue ?? 'N/A';
            $ticket->zone = $result->zone ?? 'N/A';
            $ticket->ticket_type_name = $result->ticket_type_name ?? 'N/A';
            $ticket->status = $result->status ?? 'N/A';
            $ticket->scanned_at = $result->scanned_at ?? null;
            
            // Format event date with day name
            if ($result->event_date) {
                $date = \Carbon\Carbon::parse($result->event_date);
                $dayName = $result->event_day_name ?? 'Day'; // Get day name from result
                $ticket->event_date = $dayName . ' - ' . $date->format('M j, Y') . ' at ' . $date->format('g:i A');
            }
        }

        return $ticket;
    }

    /**
     * Check if ticket is valid for current event
     */
    private function isValidForCurrentEvent(PurchaseTicket $ticket): bool
    {
        // For now, allow all sold/active/pending tickets
        // In production, you might want to check event date/time
        return in_array($ticket->status, ['sold', 'active', 'pending']);
    }

    /**
     * Mark ticket as scanned atomically
     */
    private function markTicketAsScanned(PurchaseTicket $ticket, string $gateId, int $staffUserId): void
    {
        DB::transaction(function() use ($ticket, $gateId, $staffUserId) {
            // Use Eloquent for reliable timestamp handling
            $ticket = PurchaseTicket::where('id', $ticket->id)
                ->whereIn('status', ['sold', 'active', 'pending'])
                ->first();
            
            if ($ticket) {
                $ticket->status = 'scanned';
                $ticket->scanned_at = now();
                $ticket->save();
            }
        });
    }

    /**
     * Log admittance event
     */
    private function logAdmittance(PurchaseTicket $ticket, string $result, string $gateId, int $staffUserId): void
    {
        $admittanceLog = AdmittanceLog::create([
            'purchase_ticket_id' => $ticket->id,
            'scan_time' => now(),
            'scan_result' => $result,
            'gate_id' => $gateId,
            'staff_user_id' => $staffUserId,
            'device_info' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
        ]);

        // Also log to audit trail for staff tracking
        \App\Models\AuditLog::create([
            'user_id' => $staffUserId,
            'action' => 'scan',
            'table_name' => 'tickets',
            'record_id' => $ticket->id,
            'old_values' => ['status' => $ticket->status],
            'new_values' => ['status' => 'scanned'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Ticket scanned: {$result} at {$gateId} - {$ticket->qrcode}"
        ]);
    }

    /**
     * Create validation result with performance metrics
     */
    private function createValidationResult(string $result, string $message, string $gateId, int $staffUserId, float $startTime, ?PurchaseTicket $ticket = null): array
    {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to milliseconds

        $response = [
            'result' => $result,
            'message' => $message,
            'execution_time_ms' => $executionTime,
            'gate_id' => $gateId,
            'staff_user_id' => $staffUserId,
            'timestamp' => now()->toISOString(),
        ];

        if ($ticket) {
            $response['ticket_info'] = [
                'id' => $ticket->id,
                'event_name' => $ticket->event_name ?? 'Unknown Event',
                'event_date' => $ticket->event_date ?? null,
                'venue' => $ticket->venue ?? 'N/A',
                'ticket_identifier' => "TKT-{$ticket->id}",
                'ticket_type_name' => $ticket->ticket_type_name ?? 'N/A',
                'zone' => $ticket->zone ?? 'N/A',
                'price_paid' => $ticket->price_paid ?? 0,
                'original_price' => $ticket->original_price ?? 0,
                'status' => $ticket->status ?? 'N/A',
                'scanned_at' => $ticket->scanned_at ? $ticket->scanned_at->format('Y-m-d H:i:s') : null,
            ];
        }

        // Log performance metrics
        if ($executionTime > 1000) { // Log if execution takes more than 1 second
            Log::warning('Slow ticket validation', [
                'execution_time_ms' => $executionTime,
                'qrcode' => $ticket->qrcode ?? 'unknown',
                'gate_id' => $gateId,
            ]);
        }

        return $response;
    }

    /**
     * Get validation statistics
     */
    public function getValidationStats(int $hours = 24): array
    {
        $since = now()->subHours($hours);

        $stats = DB::select("
            SELECT 
                scan_result,
                COUNT(*) as count,
                AVG(
                    CASE 
                        WHEN scan_time IS NOT NULL 
                        THEN (julianday('now') - julianday(scan_time)) * 24 * 60 * 60 * 1000
                        ELSE 0 
                    END
                ) as avg_processing_time_ms
            FROM admittance_logs 
            WHERE created_at >= ?
            GROUP BY scan_result
        ", [$since]);

        $totalScans = array_sum(array_column($stats, 'count'));

        return [
            'period_hours' => $hours,
            'total_scans' => $totalScans,
            'results' => $stats,
            'success_rate' => $totalScans > 0 ? round(($stats[0]->count ?? 0) / $totalScans * 100, 2) : 0,
        ];
    }

    /**
     * Get gate performance statistics
     */
    public function getGateStats(string $gateId, int $hours = 24): array
    {
        $since = now()->subHours($hours);

        $stats = DB::select("
            SELECT 
                scan_result,
                COUNT(*) as count,
                AVG(
                    CASE 
                        WHEN scan_time IS NOT NULL 
                        THEN (julianday('now') - julianday(scan_time)) * 24 * 60 * 60 * 1000
                        ELSE 0 
                    END
                ) as avg_processing_time_ms
            FROM admittance_logs 
            WHERE gate_id = ? AND created_at >= ?
            GROUP BY scan_result
        ", [$gateId, $since]);

        $totalScans = array_sum(array_column($stats, 'count'));

        return [
            'gate_id' => $gateId,
            'period_hours' => $hours,
            'total_scans' => $totalScans,
            'results' => $stats,
            'success_rate' => $totalScans > 0 ? round(($stats[0]->count ?? 0) / $totalScans * 100, 2) : 0,
        ];
    }

    /**
     * Clean up old admittance logs (maintenance)
     */
    public function cleanupOldLogs(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        $deletedCount = AdmittanceLog::where('created_at', '<', $cutoffDate)->delete();
        
        Log::info("Cleaned up {$deletedCount} old admittance logs");
        
        return $deletedCount;
    }
}
