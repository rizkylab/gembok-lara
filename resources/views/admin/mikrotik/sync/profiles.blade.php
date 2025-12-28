@extends('layouts.app')

@section('title', 'Sync Profiles')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('admin.partials.sidebar')
    
    <div class="lg:pl-64">
        @include('admin.partials.topbar')
        
        <main class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sync PPPoE Profiles</h1>
                    <p class="text-gray-600">Mapping PPPoE Profile Mikrotik ke Paket {{ companyName() }}</p>
                </div>
                <a href="{{ route('admin.mikrotik.sync.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    ← Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.mikrotik.sync.profiles.save') }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b">
                        <h3 class="font-semibold text-gray-700">{{ count($mikrotikProfiles) }} PPPoE Profiles ditemukan</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profile Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate Limit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Speed (Mbps)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mapping ke Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buat Baru</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($mikrotikProfiles as $profile)
                                    <tr class="{{ $profile['mapped_to'] ? 'bg-green-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $profile['name'] }}</div>
                                            @if($profile['local_address'])
                                                <div class="text-xs text-gray-500">Local: {{ $profile['local_address'] }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $profile['rate_limit'] ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($profile['speed_mbps'] > 0)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                                    {{ $profile['speed_mbps'] }} Mbps
                                                </span>
                                            @else
                                                <span class="text-gray-400">Unlimited</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($profile['mapped_to'])
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">
                                                    ✓ {{ $profile['mapped_to'] }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">
                                                    Belum mapping
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="mappings[{{ $profile['name'] }}]" 
                                                    class="mapping-select border-gray-300 rounded-md shadow-sm text-sm"
                                                    data-profile="{{ $profile['name'] }}">
                                                <option value="">-- Pilih Paket --</option>
                                                @foreach($localPackages as $package)
                                                    <option value="{{ $package->id }}" 
                                                            {{ $profile['mapped_package_id'] == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }} ({{ $package->speed }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(!$profile['mapped_to'])
                                                <input type="checkbox" 
                                                       name="create_new[]" 
                                                       value="{{ $profile['name'] }}"
                                                       class="create-new-checkbox rounded border-gray-300 text-blue-600"
                                                       data-profile="{{ $profile['name'] }}">
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(!$profile['mapped_to'])
                                                <input type="number" 
                                                       name="prices[{{ $profile['name'] }}]" 
                                                       class="price-input w-28 border-gray-300 rounded-md shadow-sm text-sm"
                                                       placeholder="0"
                                                       data-profile="{{ $profile['name'] }}"
                                                       disabled>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada PPPoE Profile ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 bg-gray-50 border-t flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span class="inline-block w-3 h-3 bg-green-100 rounded mr-1"></span> Sudah ter-mapping
                            <span class="inline-block w-3 h-3 bg-yellow-100 rounded ml-4 mr-1"></span> Belum mapping
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan Mapping
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle price input when create new checkbox is checked
    document.querySelectorAll('.create-new-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const profile = this.dataset.profile;
            const priceInput = document.querySelector(`.price-input[data-profile="${profile}"]`);
            const mappingSelect = document.querySelector(`.mapping-select[data-profile="${profile}"]`);
            
            if (this.checked) {
                priceInput.disabled = false;
                mappingSelect.disabled = true;
                mappingSelect.value = '';
            } else {
                priceInput.disabled = true;
                priceInput.value = '';
                mappingSelect.disabled = false;
            }
        });
    });

    // Disable create new when mapping is selected
    document.querySelectorAll('.mapping-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const profile = this.dataset.profile;
            const checkbox = document.querySelector(`.create-new-checkbox[data-profile="${profile}"]`);
            const priceInput = document.querySelector(`.price-input[data-profile="${profile}"]`);
            
            if (checkbox) {
                if (this.value) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    if (priceInput) {
                        priceInput.disabled = true;
                        priceInput.value = '';
                    }
                } else {
                    checkbox.disabled = false;
                }
            }
        });
    });
});
</script>
@endsection
