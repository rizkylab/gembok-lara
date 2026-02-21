<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Technician::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $technicians = $query->latest()->paginate(20);

        return view('admin.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:technicians,phone',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:technician,supervisor,installer',
            'notes' => 'nullable|string',
            'area_coverage' => 'nullable|string',
            'whatsapp_group_id' => 'nullable|string',
        ]);

        $validated['is_active'] = true;
        $validated['join_date'] = now();
        $validated['password'] = \Illuminate\Support\Facades\Hash::make('password');
        $validated['username'] = $request->email ? explode('@', $request->email)[0] : strtolower(str_replace(' ', '', $request->name));

        \App\Models\Technician::create($validated);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician created successfully! Default password is: password');
    }

    public function show(\App\Models\Technician $technician)
    {
        return view('admin.technicians.show', compact('technician'));
    }

    public function edit(\App\Models\Technician $technician)
    {
        return view('admin.technicians.edit', compact('technician'));
    }

    public function update(Request $request, \App\Models\Technician $technician)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:technicians,phone,' . $technician->id,
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:technician,supervisor,installer',
            'notes' => 'nullable|string',
            'area_coverage' => 'nullable|string',
            'whatsapp_group_id' => 'nullable|string',
            'is_active' => 'nullable|in:0,1',
        ]);

        $validated['is_active'] = $request->input('is_active', 0) == 1;

        $technician->update($validated);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician updated successfully!');
    }

    public function destroy(\App\Models\Technician $technician)
    {
        $technician->delete();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician deleted successfully!');
    }
}
