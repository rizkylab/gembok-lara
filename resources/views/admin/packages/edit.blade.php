@extends('layouts.app')

@section('title', 'Edit Package')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Top Bar -->
        @include('admin.partials.topbar')

        <!-- Page Content -->
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.packages.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Package</h1>
                        <p class="text-gray-600 mt-1">Update package information</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="max-w-3xl">
                <form action="{{ route('admin.packages.update', $package) }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Package Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Speed -->
                        <div>
                            <label for="speed" class="block text-sm font-medium text-gray-700 mb-2">
                                Speed (Mbps)
                            </label>
                            <input type="text" name="speed" id="speed" value="{{ old('speed', $package->speed) }}" placeholder="e.g., 10, 20, 50"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('speed') border-red-500 @enderror">
                            @error('speed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax Rate (%)
                            </label>
                            <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $package->tax_rate) }}" step="0.01" min="0" max="100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tax_rate') border-red-500 @enderror">
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PPPoE Profile -->
                        <div>
                            <label for="pppoe_profile" class="block text-sm font-medium text-gray-700 mb-2">
                                PPPoE Profile
                            </label>
                            <input type="text" name="pppoe_profile" id="pppoe_profile" value="{{ old('pppoe_profile', $package->pppoe_profile) }}" placeholder="e.g., 10Mbps"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pppoe_profile') border-red-500 @enderror">
                            @error('pppoe_profile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4" placeholder="Package description..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active Package
                            </label>
                        </div>

                        <!-- Package Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Package Information</p>
                                    <p class="text-sm text-blue-700 mt-1">
                                        This package currently has <strong>{{ $package->customers()->count() }}</strong> active subscribers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.packages.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                            <i class="fas fa-save mr-2"></i>
                            Update Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
