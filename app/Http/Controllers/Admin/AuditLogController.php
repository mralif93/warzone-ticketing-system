<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
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

        // Filter by table
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $auditLogs = $query->latest()->paginate(20);
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        
        // Get actual tables from database
        $tablesFromDb = AuditLog::select('table_name')->distinct()->pluck('table_name');
        
        // Predefined list of all possible modules for complete coverage
        $allModules = [
            'events',
            'orders',
            'tickets',
            'payments',
            'users',
            'settings',
            'purchase',
            'system'
        ];
        
        // Merge actual tables with predefined modules (removes duplicates)
        $tables = collect($allModules)->merge($tablesFromDb)->unique()->sort()->values();
        
        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.audit-logs.index', compact('auditLogs', 'actions', 'tables', 'users'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        
        // Get raw attributes to bypass accessors
        $oldValuesRaw = $auditLog->getRawOriginal('old_values');
        $newValuesRaw = $auditLog->getRawOriginal('new_values');
        
        // Parse JSON strings if needed
        if (is_string($oldValuesRaw) && !empty($oldValuesRaw)) {
            $auditLog->old_values = json_decode($oldValuesRaw, true);
        }
        if (is_string($newValuesRaw) && !empty($newValuesRaw)) {
            $auditLog->new_values = json_decode($newValuesRaw, true);
        }
        
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        // Apply same filters as index
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

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $auditLogs = $query->latest()->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Table',
                'Record ID',
                'Old Values',
                'New Values',
                'IP Address',
                'User Agent',
                'Created At'
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->table_name,
                    $log->record_id,
                    $log->old_values ? json_encode($log->old_values) : '',
                    $log->new_values ? json_encode($log->new_values) : '',
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old audit logs
     */
    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $deletedCount = AuditLog::where('created_at', '<', now()->subDays($request->days))->delete();

        return back()->with('success', "Cleared {$deletedCount} audit logs older than {$request->days} days.");
    }
}
