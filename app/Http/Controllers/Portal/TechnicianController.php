<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\Odp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $technician = Technician::where('username', $request->username)
                                ->orWhere('email', $request->username)
                                ->first();

        if ($technician && Hash::check($request->password, $technician->password)) {
            Auth::loginUsingId($technician->user_id ?? $technician->id);
            session(['technician_id' => $technician->id]);
            return redirect()->route('technician.dashboard');
        }

        return back()->with('error', 'Username/Email atau password salah');
    }

    public function logout()
    {
        session()->forget('technician_id');
        Auth::logout();
        return redirect()->route('technician.login');
    }

    public function dashboard()
    {
        $technician = Technician::find(session('technician_id'));
        
        // For now, using placeholder data since we don't have a Task model yet
        $todayTasks = 0;
        $completedTasks = 0;
        $pendingTasks = 0;
        $monthTasks = 0;
        $tasks = collect([]);

        return view('technician.dashboard', compact(
            'technician', 'todayTasks', 'completedTasks', 
            'pendingTasks', 'monthTasks', 'tasks'
        ));
    }

    public function tasks(Request $request)
    {
        $technician = Technician::find(session('technician_id'));
        $tasks = collect([]); // Placeholder

        return view('technician.tasks', compact('technician', 'tasks'));
    }

    public function showTask($taskId)
    {
        $technician = Technician::find(session('technician_id'));
        $task = null; // Placeholder

        return view('technician.task-detail', compact('technician', 'task'));
    }

    public function updateTask(Request $request, $taskId)
    {
        // TODO: Implement task update logic
        return back()->with('success', 'Status tugas berhasil diperbarui');
    }

    public function installations()
    {
        $technician = Technician::find(session('technician_id'));
        
        // Get customers pending installation
        $installations = Customer::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('technician.installations', compact('technician', 'installations'));
    }

    public function repairs()
    {
        $technician = Technician::find(session('technician_id'));
        
        // Get customers with issues (suspended or reported)
        $repairs = Customer::where('status', 'suspended')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('technician.repairs', compact('technician', 'repairs'));
    }

    public function map()
    {
        $technician = Technician::find(session('technician_id'));
        
        // Get ODPs with coordinates
        $odps = Odp::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Get customers with coordinates
        $customers = Customer::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('technician.map', compact('technician', 'odps', 'customers'));
    }

    public function profile()
    {
        $technician = Technician::find(session('technician_id'));
        return view('technician.profile', compact('technician'));
    }
}
