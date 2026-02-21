@extends('layouts.app')

@section('title', 'Generate Vouchers')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ 
    sidebarOpen: false, 
    voucherType: '{{ request('type', 'online') }}',
    printVouchers: false
}">
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
                <p class="text-gray-600 mt-1">Create vouchers for online sales or Mikrotik hotspot</p>
                
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800">Panduan Fitur (Simpan Database vs Sync Mikrotik)</h4>
                        <ul class="text-sm text-blue-700 mt-1 list-disc list-inside space-y-1">
                            <li><strong>Simpan Database (Tanpa Centang Sync):</strong> Voucher hanya dibuat dan disimpan di sistem lokal Gembok. Belum bisa digunakan login di jaringan Mikrotik.</li>
                            <li><strong>Sync ke Mikrotik (Dicentang):</strong> Data voucher akan langsung dikirim (Push) ke router Mikrotik sehingga pelanggan bisa langsung menggunakannya. <em>(Proses ini mungkin membutuhkan waktu sedikit lebih lama tergantung jumlah voucher yang dibuat).</em></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-md p-6 max-w-2xl">
                <form action="{{ route('admin.vouchers.generate.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Voucher Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-tags mr-2 text-blue-600"></i>Voucher Type
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                                       :class="voucherType === 'online' ? 'border-cyan-500 bg-cyan-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="type" value="online" x-model="voucherType" class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-shopping-cart text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Online Sales</p>
                                            <p class="text-xs text-gray-500">For web purchases</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                                       :class="voucherType === 'hotspot' ? 'border-cyan-500 bg-cyan-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="type" value="hotspot" x-model="voucherType" class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-wifi text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Hotspot Mikrotik</p>
                                            <p class="text-xs text-gray-500">Sync to router</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Online Voucher Options -->
                        <div x-show="voucherType === 'online'" x-cloak>
                            <label for="pricing_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-box mr-2 text-blue-600"></i>Voucher Package
                            </label>
                            <select name="pricing_id" id="pricing_id" :required="voucherType === 'online'"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Select Package</option>
                                @foreach($pricings as $pricing)
                                    <option value="{{ $pricing->id }}">
                                        {{ $pricing->package_name }} - Rp {{ number_format($pricing->customer_price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hotspot Voucher Options -->
                        <div x-show="voucherType === 'hotspot'" x-cloak class="space-y-4">
                            <div>
                                <label for="profile_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group mr-2 text-green-600"></i>Hotspot Profile
                                </label>
                                <select name="profile_id" id="profile_id" :required="voucherType === 'hotspot'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">Select Profile</option>
                                    @foreach($hotspotProfiles as $profile)
                                        <option value="{{ $profile->id }}">
                                            {{ $profile->name }} 
                                            @if($profile->rate_limit) ({{ $profile->rate_limit }}) @endif
                                            @if($profile->price > 0) - Rp {{ number_format($profile->price, 0, ',', '.') }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if($hotspotProfiles->isEmpty())
                                    <p class="text-sm text-yellow-600 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        No profiles found. <a href="{{ route('admin.vouchers.hotspot.profiles.create') }}" class="underline">Create one</a>
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="limit_uptime" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-green-600"></i>Limit Uptime (Optional)
                                </label>
                                <input type="text" name="limit_uptime" id="limit_uptime" placeholder="e.g., 1h, 3h, 1d"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Override profile session timeout</p>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calculator mr-2 text-blue-600"></i>Quantity
                            </label>
                            <input type="number" name="quantity" id="quantity" value="10" min="1" max="1000" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Maximum 1000 vouchers per generation</p>
                        </div>

                        <!-- Code Settings -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prefix" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-font mr-2 text-blue-600"></i>Code Prefix
                                </label>
                                <input type="text" name="prefix" id="prefix" value="VC" maxlength="10"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent uppercase">
                            </div>
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-ruler mr-2 text-blue-600"></i>Code Length
                                </label>
                                <input type="number" name="length" id="length" value="6" min="4" max="12"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Hotspot Options -->
                        <div x-show="voucherType === 'hotspot'" x-cloak class="space-y-3 p-4 bg-gray-50 rounded-lg">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="sync_to_mikrotik" value="1" checked
                                    class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500 mr-3">
                                <span class="text-sm text-gray-700">Sync to Mikrotik immediately</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="print_vouchers" value="1" x-model="printVouchers"
                                    class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500 mr-3">
                                <span class="text-sm text-gray-700">Open print page after generation</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 flex items-center justify-end space-x-4 border-t">
                            <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg hover:from-cyan-600 hover:to-blue-700 transition transform hover:scale-105 shadow-lg">
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
