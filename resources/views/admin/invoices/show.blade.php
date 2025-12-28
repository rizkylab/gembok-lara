@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.invoices.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Invoice Details</h1>
                        <p class="text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <!-- Invoice Header -->
                    <div class="flex justify-between items-start mb-8 pb-6 border-b">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ companyName() }}</h2>
                            <p class="text-gray-600 mt-2">ISP Management System</p>
                        </div>
                        <div class="text-right">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ strtoupper($invoice->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Invoice Info -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-3">BILL TO:</h3>
                            <p class="font-bold text-gray-900">{{ $invoice->customer->name }}</p>
                            <p class="text-gray-600">{{ $invoice->customer->phone }}</p>
                            <p class="text-gray-600">{{ $invoice->customer->email }}</p>
                            <p class="text-gray-600 mt-2">{{ $invoice->customer->address }}</p>
                        </div>
                        <div class="text-right">
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Invoice Number</p>
                                <p class="font-mono font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Invoice Date</p>
                                <p class="font-medium text-gray-900">{{ $invoice->created_at->format('d M Y') }}</p>
                            </div>
                            @if($invoice->due_date)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Due Date</p>
                                <p class="font-medium text-gray-900">{{ $invoice->due_date->format('d M Y') }}</p>
                            </div>
                            @endif
                            @if($invoice->paid_date)
                            <div>
                                <p class="text-sm text-gray-600">Paid Date</p>
                                <p class="font-medium text-green-600">{{ $invoice->paid_date->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-8">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-gray-900">{{ $invoice->package->name ?? 'Service' }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($invoice->invoice_type) }} - {{ $invoice->description ?? 'Monthly subscription' }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-right font-medium text-gray-900">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if($invoice->tax_amount > 0)
                                <tr>
                                    <td class="px-4 py-4 text-right font-medium text-gray-600">Tax</td>
                                    <td class="px-4 py-4 text-right font-medium text-gray-900">
                                        Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-4 py-4 text-right font-bold text-gray-900">TOTAL</td>
                                    <td class="px-4 py-4 text-right font-bold text-2xl text-gray-900">
                                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        @if($invoice->status === 'unpaid')
                        <form action="{{ route('admin.invoices.pay', $invoice) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-check-circle mr-2"></i>Mark as Paid
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-print mr-2"></i>Print Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
