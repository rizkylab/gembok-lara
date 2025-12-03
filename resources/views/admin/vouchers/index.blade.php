@extends('layouts.app')

@section('title', 'Vouchers')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Voucher System</h1>
                    <p class="text-gray-600 mt-1">Manage voucher sales and pricing</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.vouchers.pricing') }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-tags mr-2"></i>Pricing
                    </a>
                    <a href="{{ route('admin.vouchers.generate') }}" class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition shadow-lg">
                        <i class="fas fa-magic mr-2"></i>Generate
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Sales</p>
                            <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Vouchers Sold</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_vouchers']) }}</h3>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center text-cyan-600">
                            <i class="fas fa-ticket-alt text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Active Packages</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_pricing'] }}</h3>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Purchases -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Purchases</h2>
                    <a href="{{ route('admin.vouchers.purchases') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recent_purchases as $purchase)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $purchase->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $purchase->phone_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $purchase->pricing->package_name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($purchase->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $purchase->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $purchase->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
                                        <p>No purchases yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
