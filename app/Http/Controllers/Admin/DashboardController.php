<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => \App\Models\Customer::count(),
            'active_customers' => \App\Models\Customer::where('status', 'active')->count(),
            'total_packages' => \App\Models\Package::count(),
            'total_invoices' => \App\Models\Invoice::count(),
            'unpaid_invoices' => \App\Models\Invoice::where('status', 'unpaid')->count(),
            'total_revenue' => \App\Models\Invoice::where('status', 'paid')->sum('amount'),
            'pending_revenue' => \App\Models\Invoice::where('status', 'unpaid')->sum('amount'),
            'total_technicians' => \App\Models\Technician::count(),
            'total_collectors' => \App\Models\Collector::count(),
            'total_agents' => \App\Models\Agent::count(),
            'total_odps' => \App\Models\Odp::count(),
        ];

        $recent_invoices = \App\Models\Invoice::with(['customer', 'package'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_customers = \App\Models\Customer::with('package')
            ->latest()
            ->limit(5)
            ->get();

        // Revenue chart data (last 6 months)
        $revenueData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $revenueData[] = \App\Models\Invoice::where('status', 'paid')
                ->whereYear('paid_date', $date->year)
                ->whereMonth('paid_date', $date->month)
                ->sum('amount');
        }

        // Customer growth chart (last 6 months)
        $customerGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $customerGrowth[] = \App\Models\Customer::whereYear('join_date', $date->year)
                ->whereMonth('join_date', $date->month)
                ->count();
        }

        // Package distribution
        $packageStats = \App\Models\Package::withCount('customers')->get();

        // Invoice status distribution
        $invoiceStats = [
            'paid' => \App\Models\Invoice::where('status', 'paid')->count(),
            'unpaid' => \App\Models\Invoice::where('status', 'unpaid')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recent_invoices', 
            'recent_customers',
            'revenueData',
            'customerGrowth',
            'months',
            'packageStats',
            'invoiceStats'
        ));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
