@extends('layouts.app')

@section('title', 'Tambah OLT')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Tambah OLT</h1>
                        <p class="text-gray-600">Daftarkan perangkat OLT baru</p>
                    </div>
                    <a href="{{ route('admin.olt.index') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('admin.olt.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama OLT</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="OLT-C300">
                                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select name="brand" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="">Pilih Brand</option>
                                    <option value="ZTE" {{ old('brand') == 'ZTE' ? 'selected' : '' }}>ZTE</option>
                                    <option value="Huawei" {{ old('brand') == 'Huawei' ? 'selected' : '' }}>Huawei</option>
                                    <option value="FiberHome" {{ old('brand') == 'FiberHome' ? 'selected' : '' }}>FiberHome</option>
                                    <option value="Nokia" {{ old('brand') == 'Nokia' ? 'selected' : '' }}>Nokia</option>
                                    <option value="BDCOM" {{ old('brand') == 'BDCOM' ? 'selected' : '' }}>BDCOM</option>
                                    <option value="V-SOL" {{ old('brand') == 'V-SOL' ? 'selected' : '' }}>V-SOL</option>
                                    <option value="Other" {{ old('brand') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                <input type="text" name="model" value="{{ old('model') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="C300, MA5608T, AN5516-01">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="192.168.1.1">
                                @error('ip_address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total PON Ports</label>
                                <input type="number" name="total_pon_ports" value="{{ old('total_pon_ports', 8) }}" min="1" max="128"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2 border-t pt-4 mt-2">
                                <h3 class="font-medium text-gray-800 mb-4">SNMP Configuration</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SNMP Community</label>
                                <input type="text" name="snmp_community" value="{{ old('snmp_community', 'public') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SNMP Port</label>
                                <input type="number" name="snmp_port" value="{{ old('snmp_port', 161) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2 border-t pt-4 mt-2">
                                <h3 class="font-medium text-gray-800 mb-4">Telnet Configuration (Optional)</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telnet Username</label>
                                <input type="text" name="telnet_username" value="{{ old('telnet_username') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telnet Password</label>
                                <input type="password" name="telnet_password"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                                <input type="text" name="location" value="{{ old('location') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Data Center Jakarta">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" rows="2"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('admin.olt.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
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
