@extends('layouts.app')

@section('title', 'Edit OLT - ' . $olt->name)

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Edit OLT</h1>
                        <p class="text-gray-600">{{ $olt->name }}</p>
                    </div>
                    <a href="{{ route('admin.olt.show', $olt) }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('admin.olt.update', $olt) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama OLT</label>
                                <input type="text" name="name" value="{{ old('name', $olt->name) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select name="brand" required class="w-full rounded-lg border-gray-300 shadow-sm">
                                    @foreach(['ZTE', 'Huawei', 'FiberHome', 'Nokia', 'BDCOM', 'V-SOL', 'Other'] as $brand)
                                    <option value="{{ $brand }}" {{ old('brand', $olt->brand) == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                <input type="text" name="model" value="{{ old('model', $olt->model) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $olt->ip_address) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm">
                                    <option value="online" {{ $olt->status == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ $olt->status == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="maintenance" {{ $olt->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>

                            <div class="col-span-2 border-t pt-4 mt-2">
                                <h3 class="font-medium text-gray-800 mb-4">SNMP Configuration</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SNMP Community</label>
                                <input type="text" name="snmp_community" value="{{ old('snmp_community', $olt->snmp_community) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SNMP Port</label>
                                <input type="number" name="snmp_port" value="{{ old('snmp_port', $olt->snmp_port) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2 border-t pt-4 mt-2">
                                <h3 class="font-medium text-gray-800 mb-4">Telnet Configuration</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telnet Username</label>
                                <input type="text" name="telnet_username" value="{{ old('telnet_username', $olt->telnet_username) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telnet Password</label>
                                <input type="password" name="telnet_password" placeholder="Kosongkan jika tidak diubah"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                                <input type="text" name="location" value="{{ old('location', $olt->location) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" rows="2"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500">{{ old('description', $olt->description) }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6 pt-6 border-t">
                            <form action="{{ route('admin.olt.destroy', $olt) }}" method="POST" onsubmit="return confirm('Yakin hapus OLT ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </form>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.olt.show', $olt) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                                <button type="submit" class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
