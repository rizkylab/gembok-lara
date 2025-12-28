<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beli Voucher Hotspot - {{ companyName() }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-cyan-900 to-slate-900 min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-lg border-b border-white/20">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wifi text-white"></i>
                </div>
                <span class="text-xl font-bold text-white">{{ companyName() }}</span>
            </div>
            <a href="{{ route('customer.login') }}" class="text-cyan-400 hover:text-cyan-300">
                <i class="fas fa-user mr-1"></i> Login
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8" x-data="voucherStore()">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Beli Voucher Hotspot</h1>
            <p class="text-cyan-200 text-lg">Pilih paket voucher sesuai kebutuhan Anda</p>
        </div>

        <!-- Voucher Packages -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            @forelse($packages ?? [] as $package)
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 overflow-hidden hover:border-cyan-500 transition cursor-pointer"
                 :class="{ 'ring-2 ring-cyan-500': selectedPackage == {{ $package->id }} }"
                 @click="selectPackage({{ $package->id }}, {{ $package->customer_price }}, '{{ $package->package_name }}')">
                <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-4 text-center">
                    <h3 class="text-lg font-bold text-white">{{ $package->package_name }}</h3>
                    <p class="text-cyan-100 text-sm">{{ $package->duration }} Jam</p>
                </div>
                <div class="p-5 text-center">
                    <div class="mb-4">
                        <span class="text-2xl font-bold text-white">Rp {{ number_format($package->customer_price, 0, ',', '.') }}</span>
                    </div>
                    <ul class="text-cyan-200 text-sm space-y-2 mb-4">
                        <li><i class="fas fa-clock text-cyan-400 mr-2"></i>Durasi: {{ $package->duration }} Jam</li>
                        <li><i class="fas fa-wifi text-cyan-400 mr-2"></i>Akses Internet</li>
                        @if($package->description)
                        <li><i class="fas fa-info-circle text-cyan-400 mr-2"></i>{{ $package->description }}</li>
                        @endif
                    </ul>
                    <button class="w-full py-2.5 rounded-lg font-semibold transition text-sm"
                            :class="selectedPackage == {{ $package->id }} ? 'bg-cyan-500 text-white' : 'bg-white/20 text-white hover:bg-white/30'">
                        <span x-show="selectedPackage != {{ $package->id }}">Pilih Paket</span>
                        <span x-show="selectedPackage == {{ $package->id }}"><i class="fas fa-check mr-1"></i> Dipilih</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-ticket text-6xl text-cyan-500/50 mb-4"></i>
                <p class="text-cyan-200">Belum ada paket voucher tersedia</p>
            </div>
            @endforelse
        </div>

        <!-- Purchase Form -->
        <div x-show="selectedPackage" x-transition class="max-w-lg mx-auto">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-white mb-6 text-center">Form Pembelian</h2>
                
                <form action="{{ route('voucher.purchase') }}" method="POST" @submit="processing = true">
                    @csrf
                    <input type="hidden" name="package_id" :value="selectedPackage">
                    
                    <div class="mb-4">
                        <label class="block text-cyan-200 text-sm font-medium mb-2">Paket Dipilih</label>
                        <div class="bg-white/10 rounded-lg p-3 text-white">
                            <span x-text="selectedPackageName"></span> - 
                            <span class="font-bold">Rp <span x-text="formatPrice(selectedPrice)"></span></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-cyan-200 text-sm font-medium mb-2">
                            <i class="fas fa-user mr-1"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            placeholder="Masukkan nama Anda">
                    </div>

                    <div class="mb-4">
                        <label class="block text-cyan-200 text-sm font-medium mb-2">
                            <i class="fas fa-phone mr-1"></i> Nomor WhatsApp
                        </label>
                        <input type="tel" name="phone" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            placeholder="08xxxxxxxxxx">
                        <p class="text-cyan-300 text-xs mt-1">Voucher akan dikirim ke nomor ini</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-cyan-200 text-sm font-medium mb-2">
                            <i class="fas fa-credit-card mr-1"></i> Metode Pembayaran
                        </label>
                        <select name="payment_method" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="midtrans" class="text-gray-800">Midtrans (QRIS, VA, E-Wallet)</option>
                            <option value="xendit" class="text-gray-800">Xendit (VA, E-Wallet)</option>
                        </select>
                    </div>

                    <button type="submit" 
                            :disabled="processing"
                            class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-3 rounded-lg font-semibold hover:from-cyan-600 hover:to-blue-700 transition disabled:opacity-50">
                        <span x-show="!processing">
                            <i class="fas fa-shopping-cart mr-2"></i>Beli Sekarang
                        </span>
                        <span x-show="processing">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/5 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bolt text-cyan-400 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Aktivasi Instan</h3>
                <p class="text-cyan-200 text-sm">Voucher langsung aktif setelah pembayaran berhasil</p>
            </div>
            <div class="bg-white/5 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fab fa-whatsapp text-green-400 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Kirim via WhatsApp</h3>
                <p class="text-cyan-200 text-sm">Kode voucher dikirim langsung ke WhatsApp Anda</p>
            </div>
            <div class="bg-white/5 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-blue-400 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Pembayaran Aman</h3>
                <p class="text-cyan-200 text-sm">Transaksi dijamin aman dengan payment gateway terpercaya</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/5 border-t border-white/10 mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center text-cyan-300 text-sm">
            <p>&copy; {{ date('Y') }} {{ companyName() }}. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function voucherStore() {
            return {
                selectedPackage: null,
                selectedPrice: 0,
                selectedPackageName: '',
                processing: false,
                
                selectPackage(id, price, name) {
                    this.selectedPackage = id;
                    this.selectedPrice = price;
                    this.selectedPackageName = name;
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID').format(price);
                }
            }
        }
    </script>
</body>
</html>
