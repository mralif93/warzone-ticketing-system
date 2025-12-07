<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Event;
use App\Models\Payment;
use App\Models\PurchaseTicket;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', '2020-01-01');
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Revenue report
        $revenueData = $this->getRevenueData($dateFrom, $dateTo);
        $totalRevenue = $revenueData->sum('total');

        // Ticket sales by event
        $ticketSalesByEvent = $this->getTicketSalesByEvent($dateFrom, $dateTo);

        // User registration report
        $userRegistrations = $this->getUserRegistrationData($dateFrom, $dateTo);

        // User distribution by role
        $usersByRole = $this->getUsersByRole();

        // Top customers by spending
        $topCustomers = $this->getTopCustomers();

        // Payment methods distribution
        $paymentMethods = $this->getPaymentMethodsDistribution($dateFrom, $dateTo);

        // Event performance
        $eventPerformance = $this->getEventPerformance($dateFrom, $dateTo);

        // Monthly trends
        $monthlyTrends = $this->getMonthlyTrends();

        return view('admin.reports.index', compact(
            'revenueData',
            'ticketSalesByEvent',
            'userRegistrations',
            'topCustomers',
            'usersByRole',
            'paymentMethods',
            'eventPerformance',
            'monthlyTrends',
            'totalRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Export reports to CSV
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', '2020-01-01');
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $type = $request->get('type', 'revenue');

        $filename = "reports_{$type}_{$dateFrom}_to_{$dateTo}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type, $dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'revenue':
                    $this->exportRevenueData($file, $dateFrom, $dateTo);
                    break;
                case 'tickets':
                    $this->exportTicketData($file, $dateFrom, $dateTo);
                    break;
                case 'users':
                    $this->exportUserData($file, $dateFrom, $dateTo);
                    break;
                case 'events':
                    $this->exportEventData($file, $dateFrom, $dateTo);
                    break;
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get revenue data for the specified date range
     * Uses payments table for accurate revenue tracking
     */
    private function getRevenueData($dateFrom, $dateTo)
    {
        return Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get ticket sales by event
     * Only counts tickets from paid orders (orders with successful payments)
     */
    private function getTicketSalesByEvent($dateFrom, $dateTo)
    {
        return PurchaseTicket::whereIn('purchase.status', ['sold', 'active', 'scanned'])
            ->whereBetween('purchase.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereHas('order', function($query) {
                $query->where('status', 'paid');
            })
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
    }

    /**
     * Get user registration data
     */
    private function getUserRegistrationData($dateFrom, $dateTo)
    {
        return User::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get users by role
     */
    private function getUsersByRole()
    {
        return User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get top customers by spending
     */
    private function getTopCustomers()
    {
        return User::withCount(['orders as orders_count' => function($query) {
                $query->where('status', 'paid');
            }])
            ->withSum(['orders as orders_sum_total_amount' => function($query) {
                $query->where('status', 'paid');
            }], 'total_amount')
            ->get()
            ->filter(function($user) {
                return $user->orders_sum_total_amount > 0;
            })
            ->sortByDesc('orders_sum_total_amount')
            ->take(10);
    }

    /**
     * Get payment methods distribution
     */
    private function getPaymentMethodsDistribution($dateFrom, $dateTo)
    {
        return Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('method')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get event performance
     */
    private function getEventPerformance($dateFrom, $dateTo)
    {
        return Event::withCount(['purchaseTickets as sold_tickets' => function($query) use ($dateFrom, $dateTo) {
                $query->whereIn('status', ['sold', 'active', 'scanned'])
                      ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            }])
            ->withSum(['purchaseTickets as total_revenue' => function($query) use ($dateFrom, $dateTo) {
                $query->whereIn('status', ['sold', 'active', 'scanned'])
                      ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            }], 'price_paid')
            ->orderBy('sold_tickets', 'desc')
            ->get();
    }

    /**
     * Get monthly trends
     * Uses payments table for accurate revenue tracking
     */
    private function getMonthlyTrends()
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'revenue' => Payment::where('status', 'succeeded')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
                'tickets' => PurchaseTicket::whereIn('status', ['sold', 'active', 'scanned'])
                    ->whereHas('order', function($query) {
                        $query->where('status', 'paid');
                    })
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ]);
        }
        return $months;
    }

    /**
     * Export revenue data to CSV
     * Uses payments table for accurate revenue tracking
     */
    private function exportRevenueData($file, $dateFrom, $dateTo)
    {
        fputcsv($file, ['Date', 'Revenue (RM)', 'Payments Count']);

        $revenueData = Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($revenueData as $data) {
            fputcsv($file, [$data->date, number_format($data->total, 2), $data->count]);
        }
    }

    /**
     * Export ticket data to CSV
     */
    private function exportTicketData($file, $dateFrom, $dateTo)
    {
        fputcsv($file, ['Event', 'Date', 'Tickets Sold', 'Revenue (RM)']);
        
        $ticketSales = $this->getTicketSalesByEvent($dateFrom, $dateTo);
        foreach ($ticketSales as $event) {
            fputcsv($file, [
                $event->title,
                $event->event_date->format('Y-m-d'),
                $event->count,
                number_format($event->revenue, 2)
            ]);
        }
    }

    /**
     * Export user data to CSV
     */
    private function exportUserData($file, $dateFrom, $dateTo)
    {
        fputcsv($file, ['Date', 'New Users']);
        
        $userData = $this->getUserRegistrationData($dateFrom, $dateTo);
        foreach ($userData as $data) {
            fputcsv($file, [$data->date, $data->count]);
        }
    }

    /**
     * Export event data to CSV
     */
    private function exportEventData($file, $dateFrom, $dateTo)
    {
        fputcsv($file, ['Event', 'Date', 'Tickets Sold', 'Revenue (RM)', 'Status']);
        
        $eventData = $this->getEventPerformance($dateFrom, $dateTo);
        foreach ($eventData as $event) {
            fputcsv($file, [
                $event->name,
                $event->date_time->format('Y-m-d'),
                $event->sold_tickets,
                number_format($event->total_revenue ?? 0, 2),
                $event->status
            ]);
        }
    }
}
