<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Customer;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $tickets = $query->latest()->paginate(20);

        $stats = [
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'waiting' => Ticket::where('status', 'waiting_customer')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = Technician::where('is_active', true)->orderBy('name')->get();
        return view('admin.tickets.create', compact('customers', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:billing,technical,installation,complaint,inquiry,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:technicians,id',
        ]);

        $ticket = Ticket::create($validated);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'assignedTo', 'replies.user', 'replies.customer', 'attachments']);
        $technicians = Technician::where('is_active', true)->orderBy('name')->get();
        return view('admin.tickets.show', compact('ticket', 'technicians'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
            'status' => 'nullable|in:open,in_progress,waiting_customer,resolved,closed',
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal' => $request->boolean('is_internal'),
        ]);

        if ($request->filled('status')) {
            $ticket->update([
                'status' => $request->status,
                'resolved_at' => $request->status === 'resolved' ? now() : $ticket->resolved_at,
                'closed_at' => $request->status === 'closed' ? now() : $ticket->closed_at,
            ]);
        }

        return back()->with('success', 'Reply added successfully!');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:technicians,id',
        ]);

        $ticket->update($validated);

        if ($ticket->status === 'open' && $validated['assigned_to']) {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Ticket assigned successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_customer,resolved,closed',
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : $ticket->resolved_at,
            'closed_at' => $validated['status'] === 'closed' ? now() : $ticket->closed_at,
        ]);

        return back()->with('success', 'Status updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
}
