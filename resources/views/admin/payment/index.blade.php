@extends('layouts.app')

@section('title', 'Payment Gateway')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Payment Gateway</h1>
                <p class="text-gray-600 mt-1">Kelola integrasi payment gateway</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Midtrans Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-white font-bold text-lg">
                            <i class="fas fa-credit-card mr-2"></i>Midtrans
                        </h5>
                        @if($settings['midtrans']['enabled'])
                        <span class="px-3 py-1 bg-green-500 text-white text-sm rounded-full">Active</span>
                        @else
                        <span class="px-3 py-1 bg-gray-500 text-white text-sm rounded-full">Inactive</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 text-xs rounded {{ $settings['midtrans']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $settings['midtrans']['enabled'] ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Environment</span>
                                <span class="px-2 py-1 text-xs rounded {{ $settings['midtrans']['is_production'] ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $settings['midtrans']['is_production'] ? 'Production' : 'Sandbox' }}
                                </span>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="font-semibold text-gray-900 mb-3">Supported Payment Methods:</h6>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-credit-card text-blue-500 mr-2 w-4"></i>Credit Card
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-university text-blue-500 mr-2 w-4"></i>Bank Transfer
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-wallet text-blue-500 mr-2 w-4"></i>E-Wallet
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-store text-blue-500 mr-2 w-4"></i>Convenience Store
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 mb-1">Webhook URL:</p>
                            <code class="text-xs text-cyan-600 break-all">{{ url('/api/webhooks/midtrans') }}</code>
                        </div>
                        
                        <a href="{{ route('admin.settings.midtrans') }}" class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fas fa-cog mr-2"></i>Konfigurasi
                        </a>
                    </div>
                </div>

                <!-- Xendit Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-white font-bold text-lg">
                            <i class="fas fa-bolt mr-2"></i>Xendit
                        </h5>
                        @if($settings['xendit']['enabled'])
                        <span class="px-3 py-1 bg-green-500 text-white text-sm rounded-full">Active</span>
                        @else
                        <span class="px-3 py-1 bg-gray-500 text-white text-sm rounded-full">Inactive</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 text-xs rounded {{ $settings['xendit']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $settings['xendit']['enabled'] ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="font-semibold text-gray-900 mb-3">Supported Payment Methods:</h6>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-university text-cyan-500 mr-2 w-4"></i>Virtual Account
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-wallet text-cyan-500 mr-2 w-4"></i>E-Wallet
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-qrcode text-cyan-500 mr-2 w-4"></i>QRIS
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-store text-cyan-500 mr-2 w-4"></i>Retail Outlets
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 mb-1">Webhook URL:</p>
                            <code class="text-xs text-cyan-600 break-all">{{ url('/api/webhooks/xendit') }}</code>
                        </div>
                        
                        <a href="{{ route('admin.settings.xendit') }}" class="block w-full text-center py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition">
                            <i class="fas fa-cog mr-2"></i>Konfigurasi
                        </a>
                    </div>
                </div>

                <!-- Duitku Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-white font-bold text-lg">
                            <i class="fas fa-money-bill-wave mr-2"></i>Duitku
                        </h5>
                        @if($settings['duitku']['enabled'])
                        <span class="px-3 py-1 bg-green-500 text-white text-sm rounded-full">Active</span>
                        @else
                        <span class="px-3 py-1 bg-gray-500 text-white text-sm rounded-full">Inactive</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 text-xs rounded {{ $settings['duitku']['enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $settings['duitku']['enabled'] ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Environment</span>
                                <span class="px-2 py-1 text-xs rounded {{ $settings['duitku']['is_production'] ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $settings['duitku']['is_production'] ? 'Production' : 'Sandbox' }}
                                </span>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="font-semibold text-gray-900 mb-3">Supported Payment Methods:</h6>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-qrcode text-green-500 mr-2 w-4"></i>QRIS
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-university text-green-500 mr-2 w-4"></i>Virtual Account
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-wallet text-green-500 mr-2 w-4"></i>E-Wallet
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-store text-green-500 mr-2 w-4"></i>Retail Outlets
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 mb-1">Callback URL:</p>
                            <code class="text-xs text-green-600 break-all">{{ url('/api/duitku/callback') }}</code>
                        </div>
                        
                        <a href="{{ route('admin.settings.duitku') }}" class="block w-full text-center py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                            <i class="fas fa-cog mr-2"></i>Konfigurasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Default Gateway -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h5 class="font-bold text-gray-900 mb-4"><i class="fas fa-cog mr-2 text-cyan-600"></i>Default Gateway</h5>
                <div class="flex items-center justify-between">
                    <p class="text-gray-600">Gateway yang digunakan secara default untuk pembayaran:</p>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="default_gateway" value="midtrans" {{ $settings['default_gateway'] == 'midtrans' ? 'checked' : '' }} class="mr-2 text-cyan-600">
                            <span>Midtrans</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="default_gateway" value="xendit" {{ $settings['default_gateway'] == 'xendit' ? 'checked' : '' }} class="mr-2 text-cyan-600">
                            <span>Xendit</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="default_gateway" value="duitku" {{ $settings['default_gateway'] == 'duitku' ? 'checked' : '' }} class="mr-2 text-green-600">
                            <span>Duitku</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Configuration Guide -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="font-bold text-gray-900"><i class="fas fa-book mr-2 text-cyan-600"></i>Panduan Konfigurasi</h5>
                </div>
                <div class="p-6">
                    <div x-data="{ openTab: 'midtrans' }">
                        <div class="flex space-x-2 mb-4">
                            <button @click="openTab = 'midtrans'" :class="openTab === 'midtrans' ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-lg transition">Midtrans</button>
                            <button @click="openTab = 'xendit'" :class="openTab === 'xendit' ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-lg transition">Xendit</button>
                            <button @click="openTab = 'duitku'" :class="openTab === 'duitku' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-lg transition">Duitku</button>
                        </div>
                        
                        <div x-show="openTab === 'midtrans'">
                            <p class="text-gray-600 mb-3">Konfigurasi via menu <strong>Integrasi > Midtrans</strong> atau file <code class="bg-gray-100 px-2 py-1 rounded">.env</code>:</p>
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto"># Midtrans Configuration
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false</pre>
                            <a href="https://dashboard.midtrans.com" target="_blank" class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-2"></i>Midtrans Dashboard
                            </a>
                        </div>
                        
                        <div x-show="openTab === 'xendit'" style="display: none;">
                            <p class="text-gray-600 mb-3">Konfigurasi via menu <strong>Integrasi > Xendit</strong> atau file <code class="bg-gray-100 px-2 py-1 rounded">.env</code>:</p>
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto"># Xendit Configuration
XENDIT_SECRET_KEY=your-secret-key
XENDIT_CALLBACK_TOKEN=your-callback-token</pre>
                            <a href="https://dashboard.xendit.co" target="_blank" class="inline-flex items-center mt-4 text-cyan-600 hover:text-cyan-800">
                                <i class="fas fa-external-link-alt mr-2"></i>Xendit Dashboard
                            </a>
                        </div>
                        
                        <div x-show="openTab === 'duitku'" style="display: none;">
                            <p class="text-gray-600 mb-3">Konfigurasi via menu <strong>Integrasi > Duitku</strong>:</p>
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                                    <li>Daftar akun di <a href="https://dashboard.duitku.com" target="_blank" class="text-green-600 hover:underline">Duitku Dashboard</a></li>
                                    <li>Dapatkan <strong>Merchant Code</strong> dan <strong>API Key</strong></li>
                                    <li>Masukkan ke halaman konfigurasi Duitku</li>
                                    <li>Set Callback URL: <code class="bg-gray-200 px-1 rounded text-xs">{{ url('/api/duitku/callback') }}</code></li>
                                    <li>Test koneksi untuk memastikan konfigurasi benar</li>
                                </ol>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Duitku mendukung: QRIS, Virtual Account (BCA, Mandiri, BNI, BRI, Permata, dll), E-Wallet (OVO, DANA, ShopeePay, LinkAja), dan Retail (Alfamart, Indomaret)</p>
                            <a href="https://dashboard.duitku.com" target="_blank" class="inline-flex items-center mt-2 text-green-600 hover:text-green-800">
                                <i class="fas fa-external-link-alt mr-2"></i>Duitku Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
