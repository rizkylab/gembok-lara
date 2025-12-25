@extends('layouts.app')

@section('title', 'Semua ONU')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Semua ONU</h1>
                    <p class="text-gray-600">Daftar semua perangkat ONU</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.olt.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="{{ route('admin.olt.onu.create') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                        <i class="fas fa-plus mr-2"></i>Tambah ONU
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form action="" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Cari SN, nama, MAC, customer...">
                    </div>
                    <div>
                        <select name="status" class="rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="">Semua Status</option>
                            <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="los" {{ request('status') == 'los' ? 'selected' : '' }}>LOS</option>
                            <option value="dyinggasp" {{ request('status') == 'dyinggasp' ? 'selected' : '' }}>DyingGasp</option>
                        </select>
                    </div>
                    <div>
                        <select name="olt_id" class="rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="">Semua OLT</option>
                            @foreach($olts as $olt)
                            <option value="{{ $olt->id }}" {{ request('olt_id') == $olt->id ? 'selected' : '' }}>{{ $olt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </form>
            </div>

            <!-- ONU Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">OLT</th>
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
                                    <a href="{{ route('admin.olt.show', $onu->olt) }}" class="text-cyan-600 hover:text-cyan-700">
                                        {{ $onu->olt->name }}
                                    </a>
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
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.olt.onu.show', $onu) }}" class="text-cyan-600 hover:text-cyan-700" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="rebootOnu({{ $onu->id }})" class="text-orange-600 hover:text-orange-700" title="Reboot">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada ONU ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($onus->hasPages())
                <div class="px-4 py-3 border-t">{{ $onus->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function rebootOnu(id) {
    if (!confirm('Yakin ingin reboot ONU ini?')) return;
    
    fetch(`/admin/olt/onu/${id}/reboot`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
    });
}
</script>
@endsection
