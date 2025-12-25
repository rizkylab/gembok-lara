@extends('layouts.app')

@section('title', 'Detail ONU - ' . $onu->serial_number)

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center
                        {{ $onu->status == 'online' ? 'bg-green-100' : ($onu->status == 'los' ? 'bg-red-100' : 'bg-gray-100') }}">
                        <i class="fas fa-hdd text-2xl {{ $onu->status == 'online' ? 'text-green-600' : ($onu->status == 'los' ? 'text-red-600' : 'text-gray-600') }}"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h1 class="text-2xl font-bold text-gray-800 font-mono">{{ $onu->serial_number }}</h1>
                            @php
                                $statusColors = [
                                    'online' => 'bg-green-100 text-green-700',
                                    'offline' => 'bg-gray-100 text-gray-700',
                                    'los' => 'bg-red-100 text-red-700',
                                    'dyinggasp' => 'bg-orange-100 text-orange-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $statusColors[$onu->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ strtoupper($onu->status) }}
                            </span>
                        </div>
                        <p class="text-gray-500">{{ $onu->name ?? 'Unnamed ONU' }} • {{ $onu->model ?? 'Unknown Model' }}</p>
                        <p class="text-sm text-gray-400 mt-1">
                            OLT: <a href="{{ route('admin.olt.show', $onu->olt) }}" class="text-cyan-600 hover:text-cyan-700">{{ $onu->olt->name }}</a>
                            @if($onu->pon_location)
                            • Port: {{ $onu->pon_location }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="rebootOnu()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        <i class="fas fa-sync mr-2"></i>Reboot
                    </button>
                    <a href="{{ route('admin.olt.onu.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- ONU Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Informasi ONU</h3>
                    <form action="{{ route('admin.olt.onu.update', $onu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-500 mb-1">Nama</label>
                                <input type="text" name="name" value="{{ $onu->name }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-500 mb-1">Model</label>
                                <input type="text" name="model" value="{{ $onu->model }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-500 mb-1">Customer</label>
                                <select name="customer_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $onu->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 pt-6 border-t space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">MAC Address</span>
                            <span class="font-mono">{{ $onu->mac_address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">IP Address</span>
                            <span class="font-mono">{{ $onu->ip_address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Firmware</span>
                            <span>{{ $onu->firmware_version ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Hardware</span>
                            <span>{{ $onu->hardware_version ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Optical Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Optical Signal</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">RX Power</span>
                                @php
                                    $rxStatus = $onu->rx_power >= -25 ? 'good' : ($onu->rx_power >= -28 ? 'warning' : 'critical');
                                    $rxColors = ['good' => 'text-green-600', 'warning' => 'text-orange-600', 'critical' => 'text-red-600'];
                                @endphp
                                <span class="text-2xl font-bold {{ $rxColors[$rxStatus] ?? 'text-gray-600' }}">
                                    {{ $onu->rx_power ?? '-' }} <span class="text-sm font-normal">dBm</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $rxPercent = $onu->rx_power ? min(100, max(0, (($onu->rx_power + 35) / 15) * 100)) : 0;
                                @endphp
                                <div class="h-2 rounded-full {{ $rxStatus == 'good' ? 'bg-green-500' : ($rxStatus == 'warning' ? 'bg-orange-500' : 'bg-red-500') }}" 
                                    style="width: {{ $rxPercent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Normal: -8 to -25 dBm</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">TX Power</span>
                                <span class="text-2xl font-bold text-gray-800">
                                    {{ $onu->tx_power ?? '-' }} <span class="text-sm font-normal">dBm</span>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-2xl font-bold text-gray-800">{{ $onu->temperature ?? '-' }}°C</p>
                                <p class="text-xs text-gray-500">Temperature</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-2xl font-bold text-gray-800">{{ $onu->voltage ?? '-' }}V</p>
                                <p class="text-xs text-gray-500">Voltage</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-medium text-gray-800 mb-3">Traffic</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Download</p>
                                <p class="font-bold text-green-600">{{ number_format($onu->rx_bytes / 1024 / 1024 / 1024, 2) }} GB</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Upload</p>
                                <p class="font-bold text-blue-600">{{ number_format($onu->tx_bytes / 1024 / 1024 / 1024, 2) }} GB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Status History</h3>
                    
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Last Online</span>
                            <span>{{ $onu->last_online ? $onu->last_online->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-2">
                            <span class="text-gray-500">Last Offline</span>
                            <span>{{ $onu->last_offline ? $onu->last_offline->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @if($onu->offline_reason)
                        <div class="mt-2 text-sm">
                            <span class="text-gray-500">Reason:</span>
                            <span class="text-red-600">{{ $onu->offline_reason }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($onu->statusLogs as $log)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full 
                                    {{ $log->new_status == 'online' ? 'bg-green-500' : ($log->new_status == 'los' ? 'bg-red-500' : 'bg-gray-500') }}"></span>
                                <span>{{ strtoupper($log->new_status) }}</span>
                            </div>
                            <span class="text-gray-400">{{ $log->created_at->format('d/m H:i') }}</span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-4">Belum ada riwayat</p>
                        @endforelse
                    </div>

                    <!-- Manual Status Update -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-medium text-gray-800 mb-3">Update Status Manual</h4>
                        <form action="{{ route('admin.olt.onu.status', $onu) }}" method="POST" class="flex space-x-2">
                            @csrf
                            <select name="status" class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="los">LOS</option>
                                <option value="dyinggasp">DyingGasp</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 text-sm">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete -->
            <div class="flex justify-end">
                <form action="{{ route('admin.olt.onu.destroy', $onu) }}" method="POST" onsubmit="return confirm('Yakin hapus ONU ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Hapus ONU
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function rebootOnu() {
    if (!confirm('Yakin ingin reboot ONU ini?')) return;
    
    fetch('{{ route("admin.olt.onu.reboot", $onu) }}', {
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
