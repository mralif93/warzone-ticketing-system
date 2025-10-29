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

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $users = $query->withCount(['orders', 'tickets'])
            ->paginate($perPage);

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
            'role' => 'required|in:administrator,gate_staff,counter_staff,support_staff,customer',
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
            'role' => 'required|in:administrator,gate_staff,counter_staff,support_staff,customer',
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
     * Update user status
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $oldValues = $user->toArray();
        $user->is_active = $request->is_active;
        $user->save();

        // Log the status update
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

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User '{$user->name}' has been {$status} successfully!");
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current logged-in user
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

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

    /**
     * Display a listing of trashed users
     */
    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->get('limit', 10);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 10;
        $users = $query->orderBy('deleted_at', 'desc')->paginate($perPage);

        // Statistics
        $totalTrashed = User::onlyTrashed()->count();
        $recentlyDeleted = User::onlyTrashed()->where('deleted_at', '>=', now()->subDays(7))->count();

        return view('admin.users.trashed', compact('users', 'totalTrashed', 'recentlyDeleted'));
    }

    /**
     * Restore a trashed user
     */
    public function restore(User $user)
    {
        $user->restore();

        return redirect()->route('admin.users.trashed')
                        ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a trashed user
     */
    public function forceDelete(User $user)
    {
        // Prevent permanently deleting the current logged-in user
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot permanently delete your own account.']);
        }

        $userName = $user->name;
        $user->forceDelete();

        return redirect()->route('admin.users.trashed')
                        ->with('success', "User '{$userName}' permanently deleted.");
    }
}
