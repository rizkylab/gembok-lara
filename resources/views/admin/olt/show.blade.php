@extends('layouts.app')

@section('title', 'Detail OLT - ' . $olt->name)

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-server text-white text-2xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h1 class="text-2xl font-bold text-gray-800">{{ $olt->name }}</h1>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $olt->status == 'online' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($olt->status) }}
                            </span>
                        </div>
                        <p class="text-gray-500">{{ $olt->brand }} {{ $olt->model }} • {{ $olt->ip_address }}</p>
                        <p class="text-sm text-gray-400 mt-1">
                            <i class="fas fa-clock mr-1"></i>Uptime: {{ $olt->uptime ?? 'N/A' }}
                            @if($olt->last_sync)
                            <span class="ml-3"><i class="fas fa-sync mr-1"></i>Last sync: {{ $olt->last_sync->diffForHumans() }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <form action="{{ route('admin.olt.sync', $olt) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-sync mr-2"></i>Sync
                        </button>
                    </form>
                    <a href="{{ route('admin.olt.edit', $olt) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.olt.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $olt->total_onus }}</p>
                    <p class="text-sm text-gray-500">Total ONU</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $statusCounts['online'] }}</p>
                    <p class="text-sm text-gray-500">Online</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                    <p class="text-3xl font-bold text-orange-600">{{ $statusCounts['dyinggasp'] }}</p>
                    <p class="text-sm text-gray-500">DyingGasp</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $statusCounts['los'] }}</p>
                    <p class="text-sm text-gray-500">LOS</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                    <p class="text-3xl font-bold text-gray-600">{{ $statusCounts['offline'] }}</p>
                    <p class="text-sm text-gray-500">Offline</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- OLT Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Informasi OLT</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">IP Address</span>
                            <span class="font-medium">{{ $olt->ip_address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">SNMP Port</span>
                            <span class="font-medium">{{ $olt->snmp_port }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total PON Ports</span>
                            <span class="font-medium">{{ $olt->total_pon_ports }}</span>
                        </div>
                        @if($olt->temperature)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Temperature</span>
                            <span class="font-medium {{ $olt->temperature > 50 ? 'text-red-600' : 'text-green-600' }}">{{ $olt->temperature }}°C</span>
                        </div>
                        @endif
                        @if($olt->location)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Lokasi</span>
                            <span class="font-medium">{{ $olt->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Fans -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Fan Status</h3>
                    <div class="space-y-3">
                        @forelse($olt->fans as $fan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-fan {{ $fan->status == 'online' ? 'text-green-500 animate-spin' : 'text-red-500' }}" style="animation-duration: 2s;"></i>
                                <span class="font-medium">{{ $fan->fan_name }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">{{ number_format($fan->speed_rpm) }} <span class="text-xs text-gray-500">RPM</span></p>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $fan->status == 'online' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($fan->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Tidak ada data fan</p>
                        @endforelse
                    </div>
                </div>

                <!-- PON Ports -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">PON Ports</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($olt->ponPorts as $port)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full {{ $port->status == 'up' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                <span class="text-sm font-mono">{{ $port->port_name }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $port->online_onus }}/{{ $port->total_onus }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ONU List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Daftar ONU</h3>
                    <a href="{{ route('admin.olt.onu.create') }}" class="text-sm text-cyan-600 hover:text-cyan-700">
                        <i class="fas fa-plus mr-1"></i>Tambah ONU
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RX Power</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Online</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($onus as $onu)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'online' => 'bg-green-100 text-green-700',
                                            'offline' => 'bg-gray-100 text-gray-700',
                                            'los' => 'bg-red-100 text-red-700',
                                            'dyinggasp' => 'bg-orange-100 text-orange-700',
                                            'unknown' => 'bg-gray-100 text-gray-500'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$onu->status] ?? $statusColors['unknown'] }}">
                                        {{ strtoupper($onu->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-mono text-sm">{{ $onu->serial_number }}</p>
                                    @if($onu->name)
                                    <p class="text-xs text-gray-500">{{ $onu->name }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($onu->customer)
                                    <a href="{{ route('admin.customers.show', $onu->customer) }}" class="text-cyan-600 hover:text-cyan-700">
                                        {{ $onu->customer->name }}
                                    </a>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($onu->rx_power)
                                    <span class="{{ $onu->rx_power >= -25 ? 'text-green-600' : ($onu->rx_power >= -28 ? 'text-orange-600' : 'text-red-600') }}">
                                        {{ $onu->rx_power }} dBm
                                    </span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $onu->last_online ? $onu->last_online->diffForHumans() : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.olt.onu.show', $onu) }}" class="text-cyan-600 hover:text-cyan-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada ONU terdaftar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($onus->hasPages())
                <div class="px-4 py-3 border-t">{{ $onus->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
