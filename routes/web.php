<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GateStaffController;
use App\Http\Controllers\SupportStaffController;
use App\Http\Controllers\CounterStaffController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Payment\StripeController;
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


// Maintenance routes (must be first to avoid middleware conflicts)
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
Route::post('/maintenance/toggle', [MaintenanceController::class, 'toggle'])->name('maintenance.toggle');

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
Route::middleware(['auth'])->group(function () {
    // Main dashboard redirect
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->hasRole('administrator')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('gate_staff')) {
            return redirect()->route('gate-staff.dashboard');
        } elseif ($user->hasRole('counter_staff')) {
            return redirect()->route('counter-staff.dashboard');
        } elseif ($user->hasRole('support_staff')) {
            return redirect()->route('support-staff.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

    // Payment routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/stripe/create-intent', [StripeController::class, 'createPaymentIntent'])->name('stripe.create-intent');
        Route::post('/stripe/success', [StripeController::class, 'handlePaymentSuccess'])->name('stripe.success');
        Route::post('/stripe/failure', [StripeController::class, 'handlePaymentFailure'])->name('stripe.failure');
        Route::get('/stripe/status', [StripeController::class, 'getPaymentStatus'])->name('stripe.status');
        Route::get('/stripe/methods', [StripeController::class, 'getPaymentMethods'])->name('stripe.methods');
        Route::post('/stripe/methods', [StripeController::class, 'createPaymentMethod'])->name('stripe.create-method');
    });
    
    // Admin routes (Administrator role required)
    Route::middleware(['role:administrator'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Event management
        Route::get('/events/trashed', [AdminEventController::class, 'trashed'])->name('events.trashed');
        Route::post('/events/{event}/restore', [AdminEventController::class, 'restore'])->name('events.restore');
        Route::delete('/events/{event}/force-delete', [AdminEventController::class, 'forceDelete'])->name('events.force-delete');
        Route::resource('events', AdminEventController::class);
        Route::post('/events/{event}/change-status', [AdminEventController::class, 'changeStatus'])->name('events.change-status');
        Route::get('/events/{event}/ticket-types', [AdminEventController::class, 'getTicketTypes'])->name('events.ticket-types');
        Route::get('/events/{event}/ticket-types-test', [AdminEventController::class, 'getTicketTypes'])->name('events.ticket-types-test');
        
        // Gallery management
        Route::get('/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'all'])->name('galleries.index');
        Route::get('/events/{event}/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('events.galleries.index');
        Route::get('/events/{event}/galleries/trashed', [\App\Http\Controllers\Admin\GalleryController::class, 'trashed'])->name('events.galleries.trashed');
        Route::get('/events/{event}/galleries/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('events.galleries.create');
        Route::post('/events/{event}/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('events.galleries.store');
        Route::get('/events/{event}/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'show'])->name('events.galleries.show');
        Route::get('/events/{event}/galleries/{gallery}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('events.galleries.edit');
        Route::put('/events/{event}/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('events.galleries.update');
        Route::delete('/events/{event}/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('events.galleries.destroy');
        Route::post('/events/{event}/galleries/{gallery}/restore', [\App\Http\Controllers\Admin\GalleryController::class, 'restore'])->name('events.galleries.restore');
        Route::delete('/events/{event}/galleries/{gallery}/force-delete', [\App\Http\Controllers\Admin\GalleryController::class, 'forceDelete'])->name('events.galleries.force-delete');
        Route::post('/events/{event}/galleries/{gallery}/toggle-status', [\App\Http\Controllers\Admin\GalleryController::class, 'toggleStatus'])->name('events.galleries.toggle-status');
        
        // User management
        Route::get('/users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
        Route::post('/users/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{user}/update-password', [AdminUserController::class, 'updatePassword'])->name('users.update-password');
        Route::post('/users/{user}/update-status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');
        
        // Order management
        Route::get('/orders/trashed', [AdminOrderController::class, 'trashed'])->name('orders.trashed');
        Route::post('/orders/{order}/restore', [AdminOrderController::class, 'restore'])->name('orders.restore');
        Route::delete('/orders/{order}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('orders.force-delete');
        Route::resource('orders', AdminOrderController::class);
        Route::post('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');
        
        // Ticket management
        Route::get('/tickets/trashed', [AdminTicketController::class, 'trashed'])->name('tickets.trashed');
        Route::post('/tickets/{ticket}/restore', [AdminTicketController::class, 'restore'])->name('tickets.restore');
        Route::delete('/tickets/{ticket}/force-delete', [AdminTicketController::class, 'forceDelete'])->name('tickets.force-delete');
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
        Route::get('/purchases/trashed', [AdminPurchaseController::class, 'trashed'])->name('purchases.trashed');
        Route::post('/purchases/{purchase}/restore', [AdminPurchaseController::class, 'restore'])->name('purchases.restore');
        Route::delete('/purchases/{purchase}/force-delete', [AdminPurchaseController::class, 'forceDelete'])->name('purchases.force-delete');
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

    // Customer routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::get('/tickets/{ticket}', [CustomerController::class, 'showTicket'])->name('tickets.show');
        Route::get('/tickets/{ticket}/qr', [CustomerController::class, 'showTicketQR'])->name('tickets.qr');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('orders.show');
        Route::get('/support', [CustomerController::class, 'support'])->name('support');
    });
});

// Gate Staff routes
Route::middleware(['auth', 'gate.staff'])->prefix('gate-staff')->name('gate-staff.')->group(function () {
    Route::get('/dashboard', [GateStaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [GateStaffController::class, 'scanner'])->name('scanner');
    Route::post('/scan-ticket', [GateStaffController::class, 'scanTicket'])->name('scan-ticket');
    Route::get('/scan-history', [GateStaffController::class, 'scanHistory'])->name('scan-history');
});

// Support Staff routes
Route::middleware(['auth', 'support.staff'])->prefix('support-staff')->name('support-staff.')->group(function () {
    Route::get('/dashboard', [SupportStaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [SupportStaffController::class, 'scanner'])->name('scanner');
    Route::post('/scan-ticket', [SupportStaffController::class, 'scanTicket'])->name('scan-ticket');
    Route::post('/search-ticket', [SupportStaffController::class, 'searchTicket'])->name('search-ticket');
    Route::get('/orders/{order}', [SupportStaffController::class, 'showOrder'])->name('orders.show');
    Route::post('/tickets/{ticket}/cancel', [SupportStaffController::class, 'cancelTicket'])->name('tickets.cancel');
    Route::post('/orders/{order}/refund', [SupportStaffController::class, 'processRefund'])->name('orders.refund');
});

// Counter Staff routes
Route::middleware(['auth', 'counter.staff'])->prefix('counter-staff')->name('counter-staff.')->group(function () {
    Route::get('/dashboard', [CounterStaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [CounterStaffController::class, 'scanner'])->name('scanner');
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
Route::get('/tickets/loading/{order}', [TicketController::class, 'loading'])->name('public.tickets.loading');
Route::get('/tickets/payment/{order}', [TicketController::class, 'payment'])->name('public.tickets.payment');
Route::get('/tickets/check-order-status/{order}', [TicketController::class, 'checkOrderStatus'])->name('public.tickets.check-order-status');
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

// Stripe webhook (no auth required)
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook'])->name('stripe.webhook');