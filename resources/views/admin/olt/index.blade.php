@extends('layouts.app')

@section('title', 'OLT Management')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">OLT Management</h1>
                    <p class="text-gray-600">Monitor dan kelola perangkat OLT & ONU</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.olt.onu.index') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                        <i class="fas fa-list mr-2"></i>Semua ONU
                    </a>
                    <a href="{{ route('admin.olt.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Tambah OLT
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">OLTs</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_olts'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-ethernet mr-1"></i>{{ \App\Models\Onu::count() }} ONUs
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Online</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['online_onus'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $stats['online_percentage'] }}%
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">DyingGasp</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['dyinggasp_onus'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bolt text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-orange-600 mt-2">Power issue</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">LOS</p>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['los_onus'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-red-600 mt-2">
                        <i class="fas fa-arrow-down mr-1"></i>{{ $stats['los_percentage'] }}%
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Offline</p>
                            <p class="text-2xl font-bold text-gray-600">{{ $stats['offline_onus'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-power-off text-gray-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Other</p>
                </div>
            </div>

            <!-- OLT List -->
            @foreach($olts as $olt)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-server text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $olt->name }}</h3>
                                    <span class="px-2 py-0.5 text-xs rounded-full {{ $olt->status == 'online' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($olt->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $olt->brand }} {{ $olt->model }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $olt->uptime ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($olt->temperature)
                            <div class="text-right mr-4">
                                <p class="text-2xl font-bold text-gray-800">{{ $olt->temperature }}°C</p>
                                <p class="text-xs text-gray-500">Temperature</p>
                            </div>
                            @endif
                            <a href="{{ route('admin.olt.show', $olt) }}" class="p-2 text-gray-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.olt.edit', $olt) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>

                    <!-- ONU Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mt-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-800">{{ $olt->total_pon_ports }}</p>
                            <p class="text-xs text-gray-500">Total Port</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-800">{{ $olt->total_onus }}</p>
                            <p class="text-xs text-gray-500">Total ONU</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ $olt->online_onus }}</p>
                            <p class="text-xs text-gray-500">Online</p>
                        </div>
                        <div class="text-center p-3 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">{{ $olt->los_onus }}</p>
                            <p class="text-xs text-gray-500">LOS</p>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <p class="text-2xl font-bold text-orange-600">{{ $olt->dyinggasp_onus }}</p>
                            <p class="text-xs text-gray-500">DyingGasp</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-600">{{ $olt->offline_onus }}</p>
                            <p class="text-xs text-gray-500">Offline</p>
                        </div>
                    </div>

                    <!-- Fans -->
                    @if($olt->fans->count() > 0)
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-100">
                        @foreach($olt->fans as $fan)
                        <div class="flex items-center space-x-3 px-4 py-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-fan {{ $fan->status == 'online' ? 'text-green-500 animate-spin' : 'text-red-500' }}" style="animation-duration: 2s;"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $fan->fan_name }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($fan->speed_rpm) }} RPM</p>
                            </div>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $fan->status == 'online' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($fan->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            @if($olts->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-server text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Belum ada OLT terdaftar</p>
                <a href="{{ route('admin.olt.create') }}" class="inline-block mt-4 text-cyan-600 hover:text-cyan-700">
                    <i class="fas fa-plus mr-1"></i>Tambah OLT Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
