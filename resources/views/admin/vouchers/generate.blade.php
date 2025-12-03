@extends('layouts.app')

@section('title', 'Generate Vouchers')

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
                    <span class="text-gray-900">Generate</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Generate Vouchers</h1>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-md p-6 max-w-xl">
                <form action="{{ route('admin.vouchers.generate.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Package -->
                        <div>
                            <label for="pricing_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-box mr-2 text-blue-600"></i>Voucher Package
                            </label>
                            <select name="pricing_id" id="pricing_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Package</option>
                                @foreach($pricings as $pricing)
                                    <option value="{{ $pricing->id }}">
                                        {{ $pricing->package_name }} - Rp {{ number_format($pricing->customer_price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>Quantity
                            </label>
                            <input type="number" name="quantity" id="quantity" value="10" min="1" max="100" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Maximum 100 vouchers per generation</p>
                        </div>

                        <!-- Prefix -->
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-font mr-2 text-blue-600"></i>Code Prefix (Optional)
                            </label>
                            <input type="text" name="prefix" id="prefix" placeholder="e.g. GMB" maxlength="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
                                <i class="fas fa-magic mr-2"></i>Generate Vouchers
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
