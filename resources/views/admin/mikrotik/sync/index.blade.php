@extends('layouts.app')

@section('title', 'Sync Mikrotik')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('admin.partials.sidebar')
    
    <div class="lg:pl-64">
        @include('admin.partials.topbar')
        
        <main class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Sync Mikrotik</h1>
                <p class="text-gray-600">Import data dari Mikrotik ke {{ companyName() }}</p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$connected)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-red-800">Tidak Terhubung ke Mikrotik</h3>
                    <p class="mt-1 text-red-600">{{ $error ?? 'Silakan cek konfigurasi Mikrotik di menu Settings > Mikrotik' }}</p>
                    <a href="{{ route('admin.settings.mikrotik') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Konfigurasi Mikrotik
                    </a>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Mikrotik Data -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Data Mikrotik</h3>
                                <p class="text-sm text-gray-500">Terhubung</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">PPPoE Secrets:</span>
                                <span class="font-bold text-blue-600">{{ $stats['pppoe_secrets'] }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">PPPoE Profiles:</span>
                                <span class="font-bold text-blue-600">{{ $stats['pppoe_profiles'] }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Hotspot Users:</span>
                                <span class="font-bold text-orange-600">{{ $stats['hotspot_users'] }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Hotspot Profiles:</span>
                                <span class="font-bold text-orange-600">{{ $stats['hotspot_profiles'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Local Data -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Data {{ companyName() }}</h3>
                                <p class="text-sm text-gray-500">Database Lokal</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Customers:</span>
                                <span class="font-bold text-green-600">{{ $stats['local_customers'] }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Packages:</span>
                                <span class="font-bold text-green-600">{{ $stats['local_packages'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Sync Status</h3>
                                <p class="text-sm text-gray-500">Siap untuk sync</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sync Options -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sync Profiles -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-blue-600">
                            <h3 class="text-lg font-semibold text-white">1. Sync Profiles → Packages</h3>
                            <p class="text-blue-100 text-sm mt-1">Mapping PPPoE Profile ke Paket</p>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4">
                                Sinkronkan PPPoE Profile dari Mikrotik ke Paket di {{ companyName() }}. 
                                Anda bisa mapping ke paket yang sudah ada atau membuat paket baru.
                            </p>
                            <ul class="text-sm text-gray-500 mb-4 space-y-1">
                                <li>• {{ $stats['pppoe_profiles'] }} profiles di Mikrotik</li>
                                <li>• {{ $stats['local_packages'] }} packages di lokal</li>
                            </ul>
                            <a href="{{ route('admin.mikrotik.sync.profiles') }}" 
                               class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Sync Profiles
                            </a>
                        </div>
                    </div>

                    <!-- Import PPPoE Secrets -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-500 to-green-600">
                            <h3 class="text-lg font-semibold text-white">2. Import PPPoE Secrets</h3>
                            <p class="text-green-100 text-sm mt-1">Import user PPPoE ke Customer</p>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4">
                                Import PPPoE Secrets dari Mikrotik sebagai Customer baru. 
                                Data yang sudah ada bisa dilewati atau diupdate.
                            </p>
                            <ul class="text-sm text-gray-500 mb-4 space-y-1">
                                <li>• {{ $stats['pppoe_secrets'] }} secrets di Mikrotik</li>
                                <li>• Preview sebelum import</li>
                            </ul>
                            <a href="{{ route('admin.mikrotik.sync.secrets') }}" 
                               class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Import PPPoE
                            </a>
                        </div>
                    </div>

                    <!-- Import Hotspot Users -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-500 to-orange-600">
                            <h3 class="text-lg font-semibold text-white">3. Import Hotspot Users</h3>
                            <p class="text-orange-100 text-sm mt-1">Import user Hotspot ke Customer</p>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4">
                                Import Hotspot Users dari Mikrotik sebagai Customer baru.
                                Cocok untuk user WiFi/Voucher.
                            </p>
                            <ul class="text-sm text-gray-500 mb-4 space-y-1">
                                <li>• {{ $stats['hotspot_users'] }} users di Mikrotik</li>
                                <li>• Preview sebelum import</li>
                            </ul>
                            <a href="{{ route('admin.mikrotik.sync.hotspot') }}" 
                               class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                Import Hotspot
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-800 mb-2">💡 Tips Sync</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>1. <strong>Sync Profiles dulu</strong> - Pastikan semua profile sudah ter-mapping ke paket sebelum import secrets</li>
                        <li>2. <strong>Preview sebelum import</strong> - Selalu cek data yang akan diimport</li>
                        <li>3. <strong>Backup database</strong> - Lakukan backup sebelum import data dalam jumlah besar</li>
                        <li>4. <strong>Skip existing</strong> - Gunakan opsi skip untuk menghindari duplikasi data</li>
                    </ul>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
