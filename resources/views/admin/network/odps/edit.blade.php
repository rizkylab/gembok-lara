@extends('layouts.app')

@section('title', 'Edit ODP')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.network.odps.show', $odp) }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit ODP</h1>
                        <p class="text-gray-600 mt-1">Update ODP information</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="max-w-3xl">
                <form action="{{ route('admin.network.odps.update', $odp) }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- ODP Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ODP Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $odp->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ODP Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ODP Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" value="{{ old('code', $odp->code) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $odp->address) }}</textarea>
                        </div>

                        <!-- Coordinates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                                <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude', $odp->latitude) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                                <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude', $odp->longitude) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Capacity (Total Ports) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="capacity" value="{{ old('capacity', $odp->capacity) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('capacity') border-red-500 @enderror">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Current used ports: {{ $odp->used_ports }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status', $odp->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ old('status', $odp->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="full" {{ old('status', $odp->status) === 'full' ? 'selected' : '' }}>Full</option>
                            </select>
                        </div>

                        <!-- Installation Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Installation Date</label>
                            <input type="date" name="installation_date" value="{{ old('installation_date', $odp->installation_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes', $odp->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.network.odps.show', $odp) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                            <i class="fas fa-save mr-2"></i>Update ODP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
