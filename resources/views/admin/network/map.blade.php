@extends('layouts.app')

@section('title', 'Network Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: calc(100vh - 200px); min-height: 500px; }
    .odp-marker { background: white; border: 2px solid #3b82f6; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #3b82f6; }
    .odp-marker.full { border-color: #ef4444; color: #ef4444; }
    .odp-marker.maintenance { border-color: #f59e0b; color: #f59e0b; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Network Map</h1>
                        <p class="text-gray-600 mt-1">View all ODP locations on the map</p>
                    </div>
                    <a href="{{ route('admin.network.odps.index') }}" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition flex items-center space-x-2">
                        <i class="fas fa-list"></i>
                        <span>ODP List</span>
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total ODPs</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $odps->count() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-network-wired text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Active</p>
                            <p class="text-3xl font-bold text-green-600">{{ $odps->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Full</p>
                            <p class="text-3xl font-bold text-red-600">{{ $odps->where('status', 'full')->count() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Maintenance</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $odps->where('status', 'maintenance')->count() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-map-marked-alt mr-2 text-blue-600"></i>
                            ODP Locations
                        </h2>
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-gray-600">Active</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-gray-600">Full</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-gray-600">Maintenance</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="map"></div>
            </div>

            <!-- ODP List -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-list mr-2 text-blue-600"></i>
                    ODP Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($odps as $odp)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $odp->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $odp->code }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $odp->status === 'active' ? 'bg-green-100 text-green-800' : ($odp->status === 'full' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($odp->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $odp->address ?? 'No address' }}
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-plug mr-1"></i>
                                    {{ $odp->used_ports }}/{{ $odp->capacity }} ports
                                </span>
                                <a href="{{ route('admin.network.odps.show', $odp) }}" class="text-blue-600 hover:text-blue-800">
                                    View <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('map').setView([-6.2088, 106.8456], 12); // Default to Jakarta

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // ODP data
    const odps = @json($odps);

    // Add markers for each ODP
    const markers = [];
    odps.forEach(odp => {
        if (odp.latitude && odp.longitude) {
            const color = odp.status === 'active' ? 'blue' : (odp.status === 'full' ? 'red' : 'orange');
            
            const marker = L.marker([odp.latitude, odp.longitude], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="odp-marker ${odp.status}"><i class="fas fa-network-wired"></i></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map);

            const usagePercent = ((odp.used_ports / odp.capacity) * 100).toFixed(0);
            
            marker.bindPopup(`
                <div class="p-2">
                    <h3 class="font-bold text-lg mb-1">${odp.name}</h3>
                    <p class="text-sm text-gray-600 mb-2">${odp.code}</p>
                    <p class="text-sm mb-1"><i class="fas fa-map-marker-alt mr-1"></i>${odp.address || 'No address'}</p>
                    <p class="text-sm mb-1"><i class="fas fa-plug mr-1"></i>Capacity: ${odp.used_ports}/${odp.capacity} (${usagePercent}%)</p>
                    <p class="text-sm mb-2">
                        <span class="px-2 py-1 text-xs rounded-full ${odp.status === 'active' ? 'bg-green-100 text-green-800' : (odp.status === 'full' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')}">
                            ${odp.status.charAt(0).toUpperCase() + odp.status.slice(1)}
                        </span>
                    </p>
                    <a href="/admin/network/odps/${odp.id}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            `);

            markers.push(marker);
        }
    });

    // Fit map to show all markers
    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
</script>
@endpush
