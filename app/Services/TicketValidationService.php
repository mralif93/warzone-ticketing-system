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
                return $this->createValidationResult('INVALID', 'Ticket not found', $gateId, $staffUserId, $startTime);
            }

            // Check if ticket is already scanned
            if ($ticket->status === 'scanned' || !is_null($ticket->scanned_at)) {
                return $this->createValidationResult('DUPLICATE', 'Ticket already scanned', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Check if ticket is valid for scanning
            if (!in_array($ticket->status, ['sold', 'active', 'pending'])) {
                return $this->createValidationResult('INVALID', 'Ticket not ready for scanning', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Check if ticket is for the correct event (if event is happening today)
            if (!$this->isValidForCurrentEvent($ticket)) {
                return $this->createValidationResult('WRONG_GATE', 'Ticket not valid for current event', $gateId, $staffUserId, $startTime, $ticket);
            }

            // Mark ticket as scanned atomically
            $this->markTicketAsScanned($ticket, $gateId, $staffUserId);

            // Log successful admittance
            $this->logAdmittance($ticket, 'SUCCESS', $gateId, $staffUserId);

            return $this->createValidationResult('SUCCESS', 'Access granted', $gateId, $staffUserId, $startTime, $ticket);

        } catch (\Exception $e) {
            Log::error('Ticket validation failed', [
                'qrcode' => $qrcode,
                'gate_id' => $gateId,
                'staff_user_id' => $staffUserId,
                'error' => $e->getMessage()
            ]);

            return $this->createValidationResult('ERROR', 'Validation error occurred', $gateId, $staffUserId, $startTime);
        }
    }

    /**
     * Get ticket for validation with optimized query
     */
    private function getTicketForValidation(string $qrcode): ?PurchaseTicket
    {
        // Use raw SQL for maximum performance with proper indexing
        $result = DB::selectOne("
            SELECT pt.id, pt.event_id, pt.qrcode, pt.status, pt.scanned_at, 
                   pt.price_paid, pt.created_at,
                   e.name as event_name, e.date_time as event_date
            FROM purchase pt
            LEFT JOIN events e ON pt.event_id = e.id
            WHERE pt.qrcode = ? 
            AND pt.deleted_at IS NULL
            AND pt.status IN ('sold', 'active', 'pending')
        ", [$qrcode]);

        if (!$result) {
            return null;
        }

        // Convert to PurchaseTicket model for consistency
        $ticket = PurchaseTicket::find($result->id);
        if ($ticket) {
            // Add event information to the ticket object
            $ticket->event_name = $result->event_name;
            $ticket->event_date = $result->event_date;
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
            // Use raw SQL for atomic update
            DB::update("
                UPDATE purchase 
                SET status = 'scanned',
                    scanned_at = ?, 
                    updated_at = ?
                WHERE id = ? 
                AND status IN ('sold', 'active', 'pending')
            ", [
                now(),
                now(),
                $ticket->id
            ]);
        });
    }

    /**
     * Log admittance event
     */
    private function logAdmittance(PurchaseTicket $ticket, string $result, string $gateId, int $staffUserId): void
    {
        $admittanceLog = AdmittanceLog::create([
            'ticket_id' => $ticket->id,
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
                'ticket_identifier' => $ticket->qrcode ?? "TKT-{$ticket->id}",
                'price_paid' => $ticket->price_paid ?? 0,
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
