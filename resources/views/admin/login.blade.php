@extends('layouts.app')

@section('title', 'Admin Login')

@push('styles')
<style>
    .network-bg {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0891b2 100%);
        position: relative;
        overflow: hidden;
    }
    .network-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .network-icon {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen network-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Branding -->
        <div class="hidden lg:block text-white space-y-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="h-20 w-20 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-2xl network-icon">
                        <i class="fas fa-network-wired text-white text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-5xl font-bold tracking-tight">GEMBOK LARA</h1>
                        <p class="text-cyan-200 text-lg">ISP Management System</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6 mt-12">
                <div class="flex items-start space-x-4">
                    <div class="h-12 w-12 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-cyan-300 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Customer Management</h3>
                        <p class="text-gray-300">Kelola pelanggan, paket, dan invoice dengan mudah</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="h-12 w-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marked-alt text-blue-300 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Network Monitoring</h3>
                        <p class="text-gray-300">Monitor jaringan ODP dan infrastruktur real-time</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="h-12 w-12 bg-indigo-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chart-line text-indigo-300 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Business Analytics</h3>
                        <p class="text-gray-300">Laporan lengkap dan analisis bisnis mendalam</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full max-w-md mx-auto">
            <div class="glass-card rounded-3xl shadow-2xl p-8 lg:p-10">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-network-wired text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">GEMBOK LARA</h2>
                    <p class="text-gray-600 mt-1">ISP Management System</p>
                </div>

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to access admin dashboard</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email') }}"
                                class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                placeholder="admin@gembok.com"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me for 30 days
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl text-white font-semibold bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In to Dashboard
                    </button>
                </form>

                <!-- Default Credentials -->
                <div class="mt-6 p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-xl border border-cyan-200">
                    <div class="flex items-center justify-center text-sm">
                        <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                        <span class="text-gray-700">
                            <strong>Default:</strong> admin@gembok.com / password
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-white text-sm">
                <p>&copy; 2025 GEMBOK LARA. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
@endsection
