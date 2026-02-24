@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->ticket_number)

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700 mb-2 inline-block">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Tickets
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->ticket_number }}</h1>
                    <p class="text-gray-600">{{ $ticket->subject }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Original Message -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-cyan-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $ticket->customer->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Replies -->
                    @foreach($ticket->replies as $reply)
                    <div class="bg-white rounded-xl shadow-sm p-6 {{ $reply->is_internal ? 'border-l-4 border-yellow-400' : '' }}">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 {{ $reply->user ? 'bg-blue-100' : 'bg-cyan-100' }} rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $reply->user ? 'headset' : 'user' }} {{ $reply->user ? 'text-blue-600' : 'text-cyan-600' }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium">{{ $reply->author_name }}</span>
                                        @if($reply->is_internal)
                                        <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Internal Note</span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $reply->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Reply Form -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Add Reply</h3>
                        <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <textarea name="message" rows="4" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500"
                                placeholder="Type your reply..."></textarea>
                            
                            <div class="flex items-center justify-between mt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_internal" value="1" class="rounded text-cyan-600">
                                    <span class="ml-2 text-sm text-gray-600">Internal note (not visible to customer)</span>
                                </label>
                                
                                <div class="flex items-center space-x-3">
                                    <select name="status" class="px-3 py-2 border rounded-lg text-sm">
                                        <option value="">Keep Status</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="waiting_customer">Waiting Customer</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                    <button type="submit" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                                        <i class="fas fa-paper-plane mr-2"></i>Send Reply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Ticket Info -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Ticket Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-500">Status</label>
                                <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="mt-1">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" 
                                        class="w-full px-3 py-2 border rounded-lg text-sm">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="waiting_customer" {{ $ticket->status == 'waiting_customer' ? 'selected' : '' }}>Waiting Customer</option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </form>
                            </div>
                            
                            <div>
                                <label class="text-sm text-gray-500">Priority</label>
                                <div class="mt-1">
                                    <span class="px-3 py-1 text-sm rounded
                                        @if($ticket->priority == 'urgent') bg-red-100 text-red-800
                                        @elseif($ticket->priority == 'high') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->priority == 'medium') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm text-gray-500">Category</label>
                                <div class="mt-1 font-medium">{{ $ticket->category_label }}</div>
                            </div>
                            
                            <div>
                                <label class="text-sm text-gray-500">Assigned To Technician</label>
                                <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="mt-1">
                                    @csrf
                                    <select name="assigned_to" onchange="this.form.submit()" 
                                        class="w-full px-3 py-2 border rounded-lg text-sm">
                                        <option value="">Unassigned</option>
                                        @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ $ticket->assigned_to == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }} - {{ $technician->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            
                            <div>
                                <label class="text-sm text-gray-500">Created</label>
                                <div class="mt-1 font-medium">{{ $ticket->created_at->format('d M Y H:i') }}</div>
                            </div>
                            
                            @if($ticket->resolved_at)
                            <div>
                                <label class="text-sm text-gray-500">Resolved</label>
                                <div class="mt-1 font-medium">{{ $ticket->resolved_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Customer</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-cyan-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $ticket->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $ticket->customer->phone }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.customers.show', $ticket->customer) }}" 
                                class="block text-center text-sm text-cyan-600 hover:underline">
                                View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
