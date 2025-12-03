@extends('layouts.app')

@section('title', 'Packages Management')

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
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Packages Management</h1>
                        <p class="text-gray-600 mt-1">Manage internet packages and pricing</p>
                    </div>
                    <a href="{{ route('admin.packages.create') }}" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Package</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Packages</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $packages->count() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Active Subscribers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $packages->sum(fn($p) => $p->customers_count ?? 0) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Avg. Price</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($packages->avg('price'), 0, ',', '.') }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-cyan-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Monthly Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($packages->sum(fn($p) => $p->price * ($p->customers_count ?? 0)), 0, ',', '.') }}</p>
                        </div>
                        <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-list mr-2 text-blue-600"></i>
                            All Packages
                        </h2>
                        <div class="flex items-center space-x-3">
                            <input type="text" placeholder="Search packages..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Package Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Speed</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subscribers</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($packages as $package)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                                <i class="fas fa-wifi"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $package->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $package->description ?? 'No description' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tachometer-alt mr-2"></i>
                                            {{ $package->speed }} Mbps
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">per month</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-gray-400 mr-2"></i>
                                            <span class="font-medium text-gray-900">{{ $package->customers_count ?? 0 }}</span>
                                            <span class="text-gray-500 ml-1">customers</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.packages.edit', $package) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                                            <p class="text-gray-500 text-lg">No packages found</p>
                                            <a href="{{ route('admin.packages.create') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-plus mr-2"></i>Create your first package
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
