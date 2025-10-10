<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
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

        $users = $query->withCount(['orders', 'tickets'])
            ->paginate(15);

        $roles = User::select('role')->distinct()->pluck('role');

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Administrator,Gate Staff,Counter Staff,Support Staff,Customer',
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
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->loadCount(['orders', 'tickets']);
        $user->load(['orders.tickets', 'auditLogs']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Administrator,Gate Staff,Counter Staff,Support Staff,Customer',
            'phone_number' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $oldValues = $user->toArray();

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

        // Log the user update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
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
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' deleted successfully!");
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log the password update
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => ['password' => '***'],
            'new_values' => ['password' => '***'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
