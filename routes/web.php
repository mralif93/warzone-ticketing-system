<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GateStaffController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Authentication routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');
});

// Protected routes (require authentication)
Route::middleware(['auth', 'log.activity'])->group(function () {
    // Main dashboard redirect
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->hasRole('administrator')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('gate_staff')) {
            return redirect()->route('gate-staff.dashboard');
        } elseif ($user->hasRole('counter_staff')) {
            return redirect()->route('customer.dashboard'); // Counter staff can use customer dashboard for now
        } elseif ($user->hasRole('support_staff')) {
            return redirect()->route('customer.dashboard'); // Support staff can use customer dashboard for now
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Customer routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::get('/tickets', [CustomerController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/{ticket}', [CustomerController::class, 'showTicket'])->name('tickets.show');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('orders.show');
        Route::get('/support', [CustomerController::class, 'support'])->name('support');
    });
    
    
    // Admin routes (Administrator role required)
    Route::middleware(['role:administrator'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Event management
        Route::resource('events', AdminEventController::class);
        Route::post('/events/{event}/change-status', [AdminEventController::class, 'changeStatus'])->name('events.change-status');
        Route::get('/events/{event}/ticket-types', [AdminEventController::class, 'getTicketTypes'])->name('events.ticket-types');
        Route::get('/events/{event}/ticket-types-test', [AdminEventController::class, 'getTicketTypes'])->name('events.ticket-types-test');
        
        // User management
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{user}/update-password', [AdminUserController::class, 'updatePassword'])->name('users.update-password');
        Route::post('/users/{user}/update-status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');
        
        // Order management
        Route::resource('orders', AdminOrderController::class);
        Route::post('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');
        
        // Ticket management
        Route::resource('tickets', AdminTicketController::class);
        Route::post('/tickets/{ticket}/update-status', [AdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
        Route::post('/tickets/{ticket}/cancel', [AdminTicketController::class, 'cancel'])->name('tickets.cancel');
        Route::post('/tickets/{ticket}/mark-used', [AdminTicketController::class, 'markUsed'])->name('tickets.mark-used');
        Route::post('/tickets/bulk-update', [AdminTicketController::class, 'bulkUpdate'])->name('tickets.bulk-update');
        
        // Payment management
        Route::resource('payments', AdminPaymentController::class);
        Route::post('/payments/{payment}/change-status', [AdminPaymentController::class, 'changeStatus'])->name('payments.change-status');
        Route::post('/payments/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('payments.update-status');
        Route::post('/payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');
        Route::get('/payments/export/csv', [AdminPaymentController::class, 'export'])->name('payments.export');
        
        // Purchase management
        Route::resource('purchases', AdminPurchaseController::class);
        Route::post('/purchases/{purchase}/mark-scanned', [AdminPurchaseController::class, 'markScanned'])->name('purchases.mark-scanned');
        Route::post('/purchases/{purchase}/cancel', [AdminPurchaseController::class, 'cancel'])->name('purchases.cancel');
        
        // Audit logs
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/{auditLog}', [AdminAuditLogController::class, 'show'])->name('audit-logs.show');
        Route::get('/audit-logs/export/csv', [AdminAuditLogController::class, 'export'])->name('audit-logs.export');
        Route::post('/audit-logs/clear', [AdminAuditLogController::class, 'clear'])->name('audit-logs.clear');
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
        
        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});

// Gate Staff routes
Route::middleware(['auth', 'gate.staff'])->prefix('gate-staff')->name('gate-staff.')->group(function () {
    Route::get('/dashboard', [GateStaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [GateStaffController::class, 'scanner'])->name('scanner');
    Route::post('/scan-ticket', [GateStaffController::class, 'scanTicket'])->name('scan-ticket');
    Route::get('/scan-history', [GateStaffController::class, 'scanHistory'])->name('scan-history');
});

// Public routes (must be at the end to override protected routes)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/events', [PublicController::class, 'events'])->name('public.events');
Route::get('/events/{event}', [PublicController::class, 'showEvent'])->name('public.events.show');

// Public ticket management routes
Route::get('/events/{event}/cart', [TicketController::class, 'cart'])->name('public.tickets.cart');
Route::get('/events/{event}/checkout', [TicketController::class, 'checkout'])->name('public.tickets.checkout');
Route::post('/events/{event}/checkout', [TicketController::class, 'checkout'])->name('public.tickets.checkout.post');
Route::post('/events/{event}/purchase', [TicketController::class, 'purchase'])->name('public.tickets.purchase');
Route::get('/tickets/success/{order}', [TicketController::class, 'success'])->name('public.tickets.success');
Route::get('/tickets/failure/{event}', [TicketController::class, 'failure'])->name('public.tickets.failure');
Route::get('/tickets/confirmation/{order}', [TicketController::class, 'confirmation'])->name('public.tickets.confirmation');
Route::get('/tickets/my-tickets', [TicketController::class, 'myTickets'])->name('public.tickets.my-tickets');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('public.tickets.show');
Route::post('/tickets/release-holds', [TicketController::class, 'releaseHolds'])->name('public.tickets.release-holds');

Route::get('/about', [PublicController::class, 'about'])->name('public.about');

Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');
Route::get('/refund-policy', [PublicController::class, 'refundPolicy'])->name('public.refund-policy');
Route::get('/terms-of-service', [PublicController::class, 'termsOfService'])->name('public.terms-of-service');