<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TicketController;
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
        if (auth()->user()->hasRole('Administrator')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Customer routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::get('/events', [CustomerController::class, 'events'])->name('events');
        Route::get('/tickets', [CustomerController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/{ticket}', [CustomerController::class, 'showTicket'])->name('tickets.show');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('orders.show');
        Route::get('/support', [CustomerController::class, 'support'])->name('support');
    });
    
    // Event management routes (moved to admin routes)
    // Route::resource('events', EventController::class);
    // Route::post('/events/{event}/change-status', [EventController::class, 'changeStatus'])->name('events.change-status');
    // Route::get('/events/{event}/available-seats', [EventController::class, 'getAvailableSeats'])->name('events.available-seats');
    // Route::get('/events/seat-pricing', [EventController::class, 'getSeatPricing'])->name('events.seat-pricing');
    
    
    // Admin routes (Administrator role required)
    Route::middleware(['role:Administrator'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Event management
        Route::get('/events', [AdminController::class, 'events'])->name('events.index');
        Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
        Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
        Route::get('/events/{event}', [AdminController::class, 'showEvent'])->name('events.show');
        Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.delete');
        Route::post('/events/{event}/change-status', [AdminController::class, 'changeEventStatus'])->name('events.change-status');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        
        // Ticket management
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/{ticket}', [AdminController::class, 'showTicket'])->name('tickets.show');
        
        // Audit logs
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
        
        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        
        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});

// Gate Staff routes
Route::middleware(['auth', 'gate.staff'])->prefix('gate-staff')->name('gate-staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\GateStaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [App\Http\Controllers\GateStaffController::class, 'scanner'])->name('scanner');
    Route::post('/scan-ticket', [App\Http\Controllers\GateStaffController::class, 'scanTicket'])->name('scan-ticket');
    Route::get('/scan-history', [App\Http\Controllers\GateStaffController::class, 'scanHistory'])->name('scan-history');
});

// Public routes (must be at the end to override protected routes)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/events', [PublicController::class, 'events'])->name('public.events');
Route::get('/events/{event}', [PublicController::class, 'showEvent'])->name('public.events.show');

// Public ticket management routes
Route::get('/events/{event}/select-seats', [TicketController::class, 'select'])->name('public.tickets.select');
Route::post('/events/{event}/find-seats', [TicketController::class, 'findSeats'])->name('public.tickets.find-seats');
Route::get('/events/{event}/cart', [TicketController::class, 'cart'])->name('public.tickets.cart');
Route::post('/events/{event}/purchase', [TicketController::class, 'purchase'])->name('public.tickets.purchase');
Route::get('/tickets/confirmation/{order}', [TicketController::class, 'confirmation'])->name('public.tickets.confirmation');
Route::get('/tickets/my-tickets', [TicketController::class, 'myTickets'])->name('public.tickets.my-tickets');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('public.tickets.show');
Route::post('/tickets/release-holds', [TicketController::class, 'releaseHolds'])->name('public.tickets.release-holds');

Route::get('/about', [PublicController::class, 'about'])->name('public.about');

// Debug route to clear session (for development only)
Route::get('/debug/clear-session', function() {
    session()->flush();
    
    // Also clear held tickets from database
    \App\Models\Ticket::where('status', 'Held')->delete();
    
    return response()->json([
        'message' => 'Session and held tickets cleared successfully',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Debug route to check session status
Route::get('/debug/session-status', function() {
    return response()->json([
        'held_seats' => session('held_seats', []),
        'hold_until' => session('hold_until'),
        'current_time' => now()->toDateTimeString(),
        'session_id' => session()->getId()
    ]);
});

// Debug route to test cart logic
Route::get('/debug/test-cart', function() {
    $heldSeats = session('held_seats', []);
    $holdUntil = session('hold_until');
    
    $result = [
        'held_seats_count' => count($heldSeats),
        'hold_until' => $holdUntil,
        'current_time' => now()->toDateTimeString(),
        'session_id' => session()->getId(),
        'empty_held_seats' => empty($heldSeats),
        'hold_expired_check' => $holdUntil && !empty($holdUntil) && now()->isAfter($holdUntil)
    ];
    
    return response()->json($result);
});
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');
Route::get('/refund-policy', [PublicController::class, 'refundPolicy'])->name('public.refund-policy');
Route::get('/terms-of-service', [PublicController::class, 'termsOfService'])->name('public.terms-of-service');