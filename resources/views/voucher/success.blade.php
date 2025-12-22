<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Berhasil - Voucher Hotspot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-cyan-900 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>

            <h1 class="text-2xl font-bold text-white mb-2">Pembelian Berhasil!</h1>
            <p class="text-cyan-200 mb-6">Terima kasih atas pembelian Anda</p>

            <!-- Order Info -->
            <div class="bg-white/5 rounded-lg p-3 mb-4">
                <p class="text-cyan-300 text-xs">Order Number</p>
                <p class="text-white font-mono">{{ $purchase->order_number }}</p>
            </div>

            <!-- Voucher Details -->
            <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-xl p-6 mb-6 shadow-lg">
                <p class="text-cyan-100 text-sm mb-2">Kode Voucher</p>
                <p class="text-3xl font-mono font-bold text-white tracking-wider mb-4">{{ $purchase->voucher_code }}</p>
                
                <div class="border-t border-white/20 pt-4 mt-4">
                    <p class="text-cyan-100 text-xs mb-2">Login Hotspot</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-white/10 rounded-lg p-2">
                            <p class="text-cyan-200 text-xs">Username</p>
                            <p class="text-white font-mono font-bold">{{ $purchase->voucher_username }}</p>
                        </div>
                        <div class="bg-white/10 rounded-lg p-2">
                            <p class="text-cyan-200 text-xs">Password</p>
                            <p class="text-white font-mono font-bold">{{ $purchase->voucher_password }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Info -->
            <div class="grid grid-cols-3 gap-3 mb-6 text-sm">
                <div class="bg-white/5 rounded-lg p-3">
                    <p class="text-cyan-300 text-xs">Paket</p>
                    <p class="text-white font-medium">{{ $purchase->pricing->package_name ?? 'Voucher' }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-3">
                    <p class="text-cyan-300 text-xs">Durasi</p>
                    <p class="text-white font-medium">{{ $purchase->duration_hours }} Jam</p>
                </div>
                <div class="bg-white/5 rounded-lg p-3">
                    <p class="text-cyan-300 text-xs">Harga</p>
                    <p class="text-white font-medium">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Expiry Info -->
            @if($purchase->expires_at)
            <div class="bg-yellow-500/20 border border-yellow-500/50 rounded-lg p-3 mb-4">
                <p class="text-yellow-300 text-sm">
                    <i class="fas fa-clock mr-1"></i>
                    Berlaku sampai: {{ $purchase->expires_at->format('d/m/Y H:i') }}
                </p>
            </div>
            @endif

            <!-- Sync Status -->
            <div class="flex justify-center space-x-4 mb-6 text-xs">
                <span class="{{ $purchase->synced_to_mikrotik ? 'text-green-400' : 'text-gray-500' }}">
                    <i class="fas {{ $purchase->synced_to_mikrotik ? 'fa-check-circle' : 'fa-circle' }} mr-1"></i>Mikrotik
                </span>
                <span class="{{ $purchase->synced_to_radius ? 'text-green-400' : 'text-gray-500' }}">
                    <i class="fas {{ $purchase->synced_to_radius ? 'fa-check-circle' : 'fa-circle' }} mr-1"></i>RADIUS
                </span>
                <span class="{{ $purchase->wa_sent ? 'text-green-400' : 'text-gray-500' }}">
                    <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                </span>
            </div>

            <!-- WhatsApp Notice -->
            @if($purchase->wa_sent)
            <div class="bg-green-500/20 border border-green-500/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center text-green-400">
                    <i class="fab fa-whatsapp text-2xl mr-2"></i>
                    <span class="text-sm">Voucher telah dikirim ke {{ $purchase->customer_phone }}</span>
                </div>
            </div>
            @endif

            <!-- Instructions -->
            <div class="text-left bg-white/5 rounded-lg p-4 mb-6">
                <h3 class="text-white font-semibold mb-2">Cara Menggunakan:</h3>
                <ol class="text-cyan-200 text-sm space-y-2">
                    <li>1. Hubungkan ke WiFi Hotspot</li>
                    <li>2. Buka browser, halaman login akan muncul</li>
                    <li>3. Masukkan Username & Password di atas</li>
                    <li>4. Atau masukkan Kode Voucher</li>
                    <li>5. Nikmati internet!</li>
                </ol>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('voucher.buy') }}" class="block w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-3 rounded-lg font-semibold hover:from-cyan-600 hover:to-blue-700 transition">
                    <i class="fas fa-shopping-cart mr-2"></i>Beli Lagi
                </a>
                <a href="/" class="block w-full bg-white/10 text-white py-3 rounded-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-4">
            <button onclick="window.print()" class="text-cyan-400 hover:text-cyan-300 text-sm">
                <i class="fas fa-print mr-1"></i>Cetak Voucher
            </button>
        </div>
    </div>
</body>
</html>
