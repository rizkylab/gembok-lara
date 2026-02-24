@extends('layouts.app')

@section('title', 'Create Ticket')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700 mb-2 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tickets
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Create New Ticket</h1>
            </div>

            <div class="max-w-2xl">
                <form action="{{ route('admin.tickets.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                        <select name="customer_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500">
                        @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500">
                                <option value="inquiry" {{ old('category') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                                <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="installation" {{ old('category') == 'installation' ? 'selected' : '' }}>Installation</option>
                                <option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                            <select name="priority" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign To Technician</label>
                        <select name="assigned_to" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500">
                            <option value="">Unassigned</option>
                            @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('assigned_to') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }} - {{ $technician->phone }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="6" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-500"
                            placeholder="Describe the issue...">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            <i class="fas fa-save mr-2"></i>Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
