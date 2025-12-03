@extends('layouts.app')

@section('title', 'Voucher Pricing')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.vouchers.index') }}" class="hover:text-blue-600">Vouchers</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Pricing</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Voucher Pricing</h1>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pricings as $pricing)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $pricing->package_name }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pricing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pricing->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Duration</span>
                                    <span class="font-medium">{{ $pricing->duration }} Hours</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Customer Price</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($pricing->customer_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Agent Price</span>
                                    <span class="font-bold text-cyan-600">Rp {{ number_format($pricing->agent_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Commission</span>
                                    <span class="font-medium text-green-600">Rp {{ number_format($pricing->commission_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <form action="{{ route('admin.vouchers.pricing.update') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="id" value="{{ $pricing->id }}">
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Cust. Price</label>
                                        <input type="number" name="customer_price" value="{{ $pricing->customer_price }}" class="w-full px-3 py-2 border rounded text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Agent Price</label>
                                        <input type="number" name="agent_price" value="{{ $pricing->agent_price }}" class="w-full px-3 py-2 border rounded text-sm">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ $pricing->is_active ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                    </label>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
