@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.customers.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $customer->username ?? 'No username' }}</p>
                    </div>
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-edit mr-2"></i>Edit Customer
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-user mr-2 text-cyan-600"></i>
                            Customer Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Full Name</p>
                                <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Username</p>
                                <p class="font-medium text-gray-900">{{ $customer->username ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Phone</p>
                                <p class="font-medium text-gray-900">{{ $customer->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Address</p>
                                <p class="font-medium text-gray-900">{{ $customer->address ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : ($customer->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Join Date</p>
                                <p class="font-medium text-gray-900">{{ $customer->join_date ? $customer->join_date->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Package Information -->
                    @if($customer->package)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-box mr-2 text-blue-600"></i>
                            Current Package
                        </h2>
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg border border-cyan-200">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $customer->package->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $customer->package->speed }} Mbps</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-cyan-600">Rp {{ number_format($customer->package->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">per month</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Invoices -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">
                                <i class="fas fa-file-invoice mr-2 text-blue-600"></i>
                                Recent Invoices
                            </h2>
                            <a href="{{ route('admin.customers.invoices', $customer) }}" class="text-cyan-600 hover:text-cyan-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse($customer->invoices()->latest()->limit(5)->get() as $invoice)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $invoice->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No invoices yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Stats -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-file-invoice text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-700">Total Invoices</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $stats['total_invoices'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                    <span class="text-sm text-gray-700">Paid</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $stats['paid_invoices'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-yellow-600 mr-3"></i>
                                    <span class="text-sm text-gray-700">Unpaid</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $stats['unpaid_invoices'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Summary</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Paid</p>
                                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Unpaid</p>
                                <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($stats['total_unpaid'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="block w-full text-center bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition">
                                <i class="fas fa-edit mr-2"></i>Edit Customer
                            </a>
                            <a href="{{ route('admin.customers.invoices', $customer) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-file-invoice mr-2"></i>View Invoices
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-2"></i>Delete Customer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
