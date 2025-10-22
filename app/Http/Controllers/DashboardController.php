<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect administrators to admin dashboard
        if ($user->role === 'Administrator') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard', compact('user'));
    }
}
