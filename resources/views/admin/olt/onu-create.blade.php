@extends('layouts.app')

@section('title', 'Tambah ONU')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Tambah ONU</h1>
                        <p class="text-gray-600">Daftarkan perangkat ONU baru</p>
                    </div>
                    <a href="{{ route('admin.olt.onu.index') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('admin.olt.onu.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">OLT</label>
                                <select name="olt_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="">Pilih OLT</option>
                                    @foreach($olts as $olt)
                                    <option value="{{ $olt->id }}" {{ old('olt_id') == $olt->id ? 'selected' : '' }}>
                                        {{ $olt->name }} ({{ $olt->ip_address }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('olt_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 font-mono"
                                    placeholder="ZTEGC1234567">
                                @error('serial_number')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama ONU</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="ONU-Customer-001">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                    <input type="text" name="model" value="{{ old('model') }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="F660, HG8245H">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">MAC Address</label>
                                <input type="text" name="mac_address" value="{{ old('mac_address') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 font-mono"
                                    placeholder="AA:BB:CC:DD:EE:FF">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customer (Opsional)</label>
                                <select name="customer_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="unknown" selected>Unknown</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.olt.onu.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
