@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <!-- Dashboard Content -->
        <div class="p-6">
            <!-- Welcome Section -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Customers -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Customers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-check-circle"></i> {{ $stats['active_customers'] }} active
                            </p>
                        </div>
                        <div class="h-14 w-14 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Paid invoices</p>
                        </div>
                        <div class="h-14 w-14 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Revenue -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pending Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['pending_revenue'], 0, ',', '.') }}</p>
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-clock"></i> {{ $stats['unpaid_invoices'] }} unpaid
                            </p>
                        </div>
                        <div class="h-14 w-14 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Packages -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-cyan-500 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Packages</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_packages'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Active packages</p>
                        </div>
                        <div class="h-14 w-14 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-cyan-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-chart-line mr-2 text-cyan-600"></i>
                        Revenue Trend (Last 6 Months)
                    </h3>
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Customer Growth Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                        New Customers (Last 6 Months)
                    </h3>
                    <div style="height: 300px;">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Package Distribution & Invoice Status -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Package Distribution -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-chart-pie mr-2 text-cyan-600"></i>
                        Package Distribution
                    </h3>
                    <div style="height: 300px;" class="flex items-center justify-center">
                        <canvas id="packageChart"></canvas>
                    </div>
                </div>

                <!-- Invoice Status -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-file-invoice mr-2 text-blue-600"></i>
                        Invoice Status
                    </h3>
                    <div style="height: 300px;" class="flex items-center justify-center">
                        <canvas id="invoiceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Invoices -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-file-invoice mr-2 text-blue-600"></i>
                            Recent Invoices
                        </h3>
                        <a href="{{ route('admin.invoices.index') }}" class="text-cyan-600 hover:text-cyan-800 text-sm font-medium">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recent_invoices as $invoice)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $invoice->customer->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $invoice->invoice_number }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No invoices yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Customers -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-user-plus mr-2 text-green-600"></i>
                            Recent Customers
                        </h3>
                        <a href="{{ route('admin.customers.index') }}" class="text-cyan-600 hover:text-cyan-800 text-sm font-medium">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recent_customers as $customer)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $customer->phone }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $customer->package->name ?? 'No Package' }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No customers yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    revenueGradient.addColorStop(0, 'rgba(6, 182, 212, 0.3)');
    revenueGradient.addColorStop(1, 'rgba(6, 182, 212, 0.01)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueData),
                borderColor: 'rgb(6, 182, 212)',
                backgroundColor: revenueGradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(6, 182, 212)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgb(6, 182, 212)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 12,
                    titleColor: '#fff',
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyColor: '#fff',
                    bodyFont: {
                        size: 12
                    },
                    borderColor: 'rgba(6, 182, 212, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10,
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10
                    }
                }
            }
        }
    });

    // Customer Growth Chart
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'New Customers',
                data: @json($customerGrowth),
                backgroundColor: 'rgba(59, 130, 246, 0.85)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 12,
                    titleColor: '#fff',
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyColor: '#fff',
                    bodyFont: {
                        size: 12
                    },
                    borderColor: 'rgba(59, 130, 246, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' customers';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10,
                        stepSize: 1,
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10
                    }
                }
            }
        }
    });

    // Package Distribution Chart
    const packageCtx = document.getElementById('packageChart').getContext('2d');
    new Chart(packageCtx, {
        type: 'doughnut',
        data: {
            labels: @json($packageStats->pluck('name')),
            datasets: [{
                data: @json($packageStats->pluck('customers_count')),
                backgroundColor: [
                    'rgba(6, 182, 212, 0.85)',
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)',
                    'rgba(239, 68, 68, 0.85)',
                    'rgba(139, 92, 246, 0.85)'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10,
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 12,
                    titleColor: '#fff',
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyColor: '#fff',
                    bodyFont: {
                        size: 12
                    },
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Invoice Status Chart
    const invoiceCtx = document.getElementById('invoiceChart').getContext('2d');
    new Chart(invoiceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Unpaid'],
            datasets: [{
                data: [@json($invoiceStats['paid']), @json($invoiceStats['unpaid'])],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10,
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 12,
                    titleColor: '#fff',
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyColor: '#fff',
                    bodyFont: {
                        size: 12
                    },
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' invoices (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
