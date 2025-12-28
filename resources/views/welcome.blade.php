<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ companyName() }} - Internet Service Provider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900">
    <!-- Navigation -->
    <nav class="bg-slate-900/95 backdrop-blur-lg fixed w-full z-50 border-b border-cyan-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wifi text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-white">{{ companyName() }}</span>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#packages" class="text-gray-300 hover:text-cyan-400 transition">Paket</a>
                    <a href="#features" class="text-gray-300 hover:text-cyan-400 transition">Fitur</a>
                    <a href="#contact" class="text-gray-300 hover:text-cyan-400 transition">Kontak</a>
                    <a href="{{ route('voucher.buy') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                        <i class="fas fa-ticket mr-1"></i> Beli Voucher
                    </a>
                    <a href="{{ route('customer.login') }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-cyan-600 hover:to-blue-700 transition">
                        <i class="fas fa-user mr-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-cyan-900/30 to-slate-900"></div>
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-20 w-72 h-72 bg-cyan-500 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-blue-600 rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                Internet <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Super Cepat</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Nikmati koneksi internet fiber optik dengan kecepatan tinggi dan harga terjangkau untuk rumah dan bisnis Anda
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#packages" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-cyan-600 hover:to-blue-700 transition shadow-lg shadow-cyan-500/30">
                    <i class="fas fa-rocket mr-2"></i>Lihat Paket
                </a>
                <a href="{{ route('voucher.buy') }}" class="bg-white/10 backdrop-blur text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition border border-white/20">
                    <i class="fas fa-ticket mr-2"></i>Beli Voucher
                </a>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-cyan-400 text-2xl"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Kami menyediakan layanan internet terbaik dengan berbagai keunggulan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bolt text-cyan-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Kecepatan Tinggi</h3>
                    <p class="text-gray-400">Koneksi fiber optik hingga 100 Mbps untuk streaming dan gaming tanpa lag</p>
                </div>
                
                <div class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-green-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">24/7 Support</h3>
                    <p class="text-gray-400">Tim teknis siap membantu Anda kapan saja melalui WhatsApp dan telepon</p>
                </div>
                
                <div class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-purple-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Stabil & Aman</h3>
                    <p class="text-gray-400">Jaringan redundant dengan uptime 99.9% dan keamanan terjamin</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-20 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Pilih Paket Anda</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Berbagai pilihan paket sesuai kebutuhan Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($packages ?? [] as $index => $package)
                @php $isPopular = $index === 1; @endphp
                <div class="{{ $isPopular ? 'bg-gradient-to-b from-cyan-900/50 to-slate-800 border-2 border-cyan-500 transform md:scale-105' : 'bg-slate-800 border border-slate-700 hover:border-cyan-500/50' }} rounded-2xl overflow-hidden relative transition">
                    @if($isPopular)
                    <div class="absolute top-0 left-0 right-0 bg-cyan-500 text-white text-center py-2 text-sm font-semibold">
                        PALING POPULER
                    </div>
                    @endif
                    <div class="p-8 {{ $isPopular ? 'pt-12' : '' }}">
                        <h3 class="text-xl font-semibold text-white mb-2">{{ $package->name }}</h3>
                        <p class="text-gray-400 mb-4">{{ $package->description ?? 'Internet cepat & stabil' }}</p>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-white">Rp {{ number_format($package->price / 1000, 0) }}K</span>
                            <span class="text-gray-400">/bulan</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-300">
                                <i class="fas fa-check text-cyan-400 mr-3"></i>{{ $package->speed }}
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fas fa-check text-cyan-400 mr-3"></i>Unlimited Quota
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fas fa-check text-cyan-400 mr-3"></i>Free Installation
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fas fa-check text-cyan-400 mr-3"></i>24/7 Support
                            </li>
                        </ul>
                        <a href="{{ route('order.create', $package) }}" class="block w-full {{ $isPopular ? 'bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700' : 'bg-slate-700 hover:bg-slate-600' }} text-white py-3 rounded-lg text-center font-semibold transition">
                            <i class="fas fa-shopping-cart mr-2"></i>Pilih Paket
                        </a>
                    </div>
                </div>
                @empty
                <!-- Fallback jika tidak ada paket di database -->
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-400">Paket belum tersedia. Hubungi kami untuk informasi lebih lanjut.</p>
                    <a href="https://wa.me/6281234567890" class="inline-block mt-4 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        <i class="fab fa-whatsapp mr-2"></i>Hubungi via WhatsApp
                    </a>
                </div>
                @endforelse
            </div>
            
            <!-- Track Order Link -->
            <div class="text-center mt-12">
                <p class="text-gray-400 mb-2">Sudah pesan? Lacak status pesanan Anda</p>
                <a href="{{ route('order.track') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                    <i class="fas fa-search mr-1"></i>Lacak Pesanan
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Hubungi Kami</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Kami siap membantu Anda 24/7</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-whatsapp text-green-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">WhatsApp</h3>
                    <p class="text-gray-400">0812-3456-7890</p>
                </a>
                
                <a href="tel:+6281234567890" class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-blue-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Telepon</h3>
                    <p class="text-gray-400">0812-3456-7890</p>
                </a>
                
                <a href="mailto:{{ companyEmail() }}" class="bg-slate-700/50 rounded-2xl p-8 text-center hover:bg-slate-700 transition">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-purple-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Email</h3>
                    <p class="text-gray-400">{{ companyEmail() }}</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wifi text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-white">{{ companyName() }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">Internet Service Provider terpercaya untuk rumah dan bisnis Anda.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-cyan-400 transition">Internet Rumah</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Internet Bisnis</a></li>
                        <li><a href="{{ route('voucher.buy') }}" class="hover:text-cyan-400 transition">Voucher Hotspot</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Portal</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('customer.login') }}" class="hover:text-cyan-400 transition">Customer Portal</a></li>
                        <li><a href="{{ route('agent.login') }}" class="hover:text-cyan-400 transition">Agent Portal</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-cyan-400 transition">Admin Panel</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:bg-slate-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:bg-slate-700 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:bg-slate-700 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ companyName() }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
