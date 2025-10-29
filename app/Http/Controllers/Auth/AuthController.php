<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->hasRole('administrator')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('gate_staff')) {
                return redirect()->route('gate-staff.dashboard');
            } elseif ($user->hasRole('support_staff')) {
                return redirect()->route('support-staff.dashboard');
            } elseif ($user->hasRole('counter_staff')) {
                return redirect()->route('counter-staff.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Check if user exists and is active before attempting authentication
        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->is_active == false) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support for assistance.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Add is_active condition to credentials
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if there's an intended URL
            $intended = session('url.intended');
            
            // Redirect based on user role to preserve session message
            $user = auth()->user();
            
            // Double-check is_active status (security measure)
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact support for assistance.',
                ])->onlyInput('email');
            }
            
            // Log the login
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => ['last_login_at' => $user->last_login_at ?? null],
                'new_values' => ['last_login_at' => now()],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => "User {$user->name} logged in"
            ]);
            
            if ($user->hasRole('administrator')) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login successful!');
            } elseif ($user->hasRole('gate_staff')) {
                return redirect()->intended(route('gate-staff.dashboard'))->with('success', 'Login successful!');
            } elseif ($user->hasRole('support_staff')) {
                return redirect()->intended(route('support-staff.dashboard'))->with('success', 'Login successful!');
            } elseif ($user->hasRole('counter_staff')) {
                return redirect()->intended(route('counter-staff.dashboard'))->with('success', 'Login successful!');
            } else {
                // For customers, check if they were trying to access a specific page
                if ($intended && str_contains($intended, '/events/') && str_contains($intended, '/cart')) {
                    return redirect()->intended($intended)->with('success', 'Login successful! You can now proceed with your ticket purchase.');
                }
                return redirect()->intended(route('customer.dashboard'))->with('success', 'Login successful!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->hasRole('administrator')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('gate_staff')) {
                return redirect()->route('gate-staff.dashboard');
            } elseif ($user->hasRole('support_staff')) {
                return redirect()->route('support-staff.dashboard');
            } elseif ($user->hasRole('counter_staff')) {
                return redirect()->route('counter-staff.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }

        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'string', 'confirmed', new StrongPassword],
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'Customer', // Default role
        ]);

        // Log the user registration
        \App\Models\AuditLog::create([
            'user_id' => $user->id,
            'action' => 'create',
            'table_name' => 'users',
            'record_id' => $user->id,
            'old_values' => null,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "New customer registered: {$user->name}"
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'Registration successful! Welcome to Warzone Ticketing System.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Password reset link sent to your email!');
        }

        return back()->withErrors(['email' => 'Unable to send password reset link. Please try again.']);
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request, $token)
    {
        // Get email from query parameter (sent via temporary signed route)
        $email = $request->query('email') ?? $request->input('email');
        
        // Validate token exists and is not expired
        if ($email) {
            $tokenRecord = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();
            
            if (!$tokenRecord) {
                return redirect()->route('forgot-password')
                    ->with('error', 'This password reset token is invalid or has expired. Please request a new password reset link.');
            }
            
            // Check if token is expired (default 60 minutes)
            $expireMinutes = config('auth.passwords.users.expire', 60);
            $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
            $expiresAt = $createdAt->copy()->addMinutes($expireMinutes);
            
            if (now()->greaterThan($expiresAt)) {
                return redirect()->route('forgot-password')
                    ->with('error', 'This password reset token has expired. Please request a new password reset link.');
            }
        }
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successful! Please login with your new password.');
        }

        // Handle specific error cases
        if ($status === Password::INVALID_TOKEN) {
            return redirect()->route('forgot-password')
                ->with('error', 'This password reset token is invalid or has expired. Please request a new password reset link.');
        } elseif ($status === Password::INVALID_USER) {
            return redirect()->route('forgot-password')
                ->with('error', 'We cannot find a user with that email address.');
        } elseif ($status === Password::THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Please wait before retrying.']);
        }

        // Generic error
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Unable to reset password. Please try again.']);
    }

    /**
     * Handle logout request
     */
public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log the logout before session is invalidated
        if ($user) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => null,
                'new_values' => ['last_logout_at' => now()],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => "User {$user->name} logged out"
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been successfully logged out!');
    }
}
