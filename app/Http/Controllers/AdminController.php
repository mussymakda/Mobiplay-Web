<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return 'Admin login form works!';
        /*
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
        */
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        // Get dynamic counts for dashboard
        $stats = [
            'totalUsers' => User::count(),
            'totalDrivers' => Driver::count(),
            'activeTablets' => Driver::where('is_active', true)->count(),
            'activeAds' => Ad::where('status', Ad::STATUS_ACTIVE)->count(),
            'totalRevenue' => Payment::where('type', Payment::TYPE_DEPOSIT)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
