@extends('layouts.technician')

@section('title', 'Peta Jaringan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Peta Jaringan</h1>
        <div class="flex space-x-2">
            <button onclick="showOdps()" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
                <i class="fas fa-project-diagram mr-1"></i> ODP
            </button>
            <button onclick="showCustomers()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-users mr-1"></i> Pelanggan
            </button>
            <button onclick="toggleCables()" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition">
                <i class="fas fa-network-wired mr-1"></i> Jalur Kabel
            </button>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div id="map" style="height: 600px;"></div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h3 class="font-semibold text-gray-800 mb-3">Keterangan</h3>
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-cyan-500 rounded-full mr-2"></div>
                <span>ODP Aktif</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                <span>ODP Maintenance</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                <span>Pelanggan Aktif</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                <span>Pelanggan Suspended</span>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Initialize map
    const map = L.map('map').setView([-6.2088, 106.8456], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // ODP markers
    const odps = @json($odps ?? []);
    const odpMarkers = L.layerGroup();
    
    odps.forEach(odp => {
        if (odp.latitude && odp.longitude) {
            const color = odp.status === 'active' ? '#06b6d4' : '#eab308';
            const marker = L.circleMarker([odp.latitude, odp.longitude], {
                radius: 10,
                fillColor: color,
                color: '#fff',
                weight: 2,
                fillOpacity: 0.8
            }).bindPopup(`
                <strong>${odp.name}</strong><br>
                Kapasitas: ${odp.used_ports || 0}/${odp.total_ports || 0}<br>
                Status: ${odp.status}
            `);
            odpMarkers.addLayer(marker);
        }
    });
    odpMarkers.addTo(map);

    // Customer markers
    const customers = @json($customers ?? []);
    const customerMarkers = L.layerGroup();
    
    customers.forEach(customer => {
        if (customer.latitude && customer.longitude) {
            const color = customer.status === 'active' ? '#22c55e' : '#ef4444';
            const marker = L.circleMarker([customer.latitude, customer.longitude], {
                radius: 6,
                fillColor: color,
                color: '#fff',
                weight: 1,
                fillOpacity: 0.8
            }).bindPopup(`
                <strong>${customer.name}</strong><br>
                ${customer.address || ''}<br>
                Status: ${customer.status}
            `);
            customerMarkers.addLayer(marker);
        }
    });
    customerMarkers.addTo(map);

    // Fit bounds if we have markers
    const allMarkers = [...odps, ...customers].filter(m => m.latitude && m.longitude);
    if (allMarkers.length > 0) {
        const bounds = allMarkers.map(m => [m.latitude, m.longitude]);
        map.fitBounds(bounds, { padding: [50, 50] });
    }

    // Connect ODP and Customers with Polyline
    const cablesLayer = L.layerGroup();
    customers.forEach(customer => {
        if (customer.latitude && customer.longitude) {
            // Find ODP assigned to this customer.
            // Assuming customer has odp_id or we find the closest one if not defined natively.
            const odp = customer.odp_id ? odps.find(o => o.id === customer.odp_id) : null;
            if (odp && odp.latitude && odp.longitude) {
                const latlngs = [
                    [parseFloat(odp.latitude), parseFloat(odp.longitude)],
                    [parseFloat(customer.latitude), parseFloat(customer.longitude)]
                ];
                const polyline = L.polyline(latlngs, {
                    color: '#475569', 
                    weight: 2, 
                    dashArray: '5, 5', 
                    opacity: 0.8
                }).bindPopup(`Jalur Kabel ODP: ${odp.name} ➡️ Pelanggan: ${customer.name}`);
                cablesLayer.addLayer(polyline);
            }
        }
    });

    cablesLayer.addTo(map);

    function showOdps() {
        map.addLayer(odpMarkers);
    }

    function showCustomers() {
        map.addLayer(customerMarkers);
    }

    function toggleCables() {
        if (map.hasLayer(cablesLayer)) {
            map.removeLayer(cablesLayer);
        } else {
            map.addLayer(cablesLayer);
        }
    }
</script>
@endsection
