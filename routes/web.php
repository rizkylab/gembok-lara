<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\OdpController;
use App\Http\Controllers\Admin\TicketController;

// Public Routes
Route::get('/', function () {
    $packages = \App\Models\Package::where('is_active', true)->orderBy('price')->get();
    return view('welcome', compact('packages'));
})->name('home');

// Order Routes (Public)
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/package/{package}', [\App\Http\Controllers\OrderController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\OrderController::class, 'store'])->name('store');
    Route::get('/success/{orderNumber}', [\App\Http\Controllers\OrderController::class, 'success'])->name('success');
    Route::get('/track', [\App\Http\Controllers\OrderController::class, 'track'])->name('track');
});

// Language Switch
Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

// Default login route (redirect to admin login)
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', function () {
        return view('admin.login');
    })->name('login');
    
    Route::post('/login', [DashboardController::class, 'login'])->name('login.post');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Customer Management
        Route::resource('customers', CustomerController::class);
        Route::get('/customers/{customer}/invoices', [CustomerController::class, 'invoices'])->name('customers.invoices');
        
        // Package Management
        Route::resource('packages', PackageController::class);
        
        // Invoice Management
        Route::resource('invoices', InvoiceController::class);
        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
        Route::post('/invoices/{invoice}/send-notification', [InvoiceController::class, 'sendNotification'])->name('invoices.send-notification');
        Route::post('/invoices/{invoice}/create-payment-link', [InvoiceController::class, 'createPaymentLink'])->name('invoices.create-payment-link');
        Route::post('/invoices/{invoice}/send-payment-link', [InvoiceController::class, 'sendPaymentLink'])->name('invoices.send-payment-link');
        
        // Technician Management
        Route::resource('technicians', TechnicianController::class);
        
        // Collector Management
        Route::resource('collectors', CollectorController::class);
        Route::get('/collectors/{collector}/payments', [CollectorController::class, 'payments'])->name('collectors.payments');
        
        // Agent Management
        Route::resource('agents', AgentController::class);
        Route::get('/agents/{agent}/balance', [AgentController::class, 'balance'])->name('agents.balance');
        Route::post('/agents/{agent}/topup', [AgentController::class, 'topup'])->name('agents.topup');
        
        // Voucher Management
        Route::prefix('vouchers')->name('vouchers.')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('index');
            Route::get('/pricing', [VoucherController::class, 'pricing'])->name('pricing');
            Route::post('/pricing', [VoucherController::class, 'updatePricing'])->name('pricing.update');
            Route::get('/purchases', [VoucherController::class, 'purchases'])->name('purchases');
            Route::get('/generate', [VoucherController::class, 'generate'])->name('generate');
            Route::post('/generate', [VoucherController::class, 'storeGenerate'])->name('generate.store');
        });
        
        // ODP & Cable Network Management
        Route::prefix('network')->name('network.')->group(function () {
            Route::resource('odps', OdpController::class);
            Route::get('/odps/{odp}/cables', [OdpController::class, 'cables'])->name('odps.cables');
            Route::get('/map', [OdpController::class, 'map'])->name('map');
        });
        
        // Tickets
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('index');
            Route::get('/create', [TicketController::class, 'create'])->name('create');
            Route::post('/', [TicketController::class, 'store'])->name('store');
            Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
            Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
            Route::post('/{ticket}/assign', [TicketController::class, 'assign'])->name('assign');
            Route::post('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('status');
            Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
        });

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Change Password
        Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [DashboardController::class, 'updatePassword'])->name('change-password.update');
        
        // Mikrotik Management
        Route::prefix('mikrotik')->name('mikrotik.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MikrotikController::class, 'index'])->name('index');
            Route::get('/pppoe-active', [\App\Http\Controllers\Admin\MikrotikController::class, 'pppoeActive'])->name('pppoe.active');
            Route::get('/hotspot-active', [\App\Http\Controllers\Admin\MikrotikController::class, 'hotspotActive'])->name('hotspot.active');
            Route::post('/disconnect', [\App\Http\Controllers\Admin\MikrotikController::class, 'disconnect'])->name('disconnect');
            Route::get('/system-resource', [\App\Http\Controllers\Admin\MikrotikController::class, 'systemResource'])->name('system.resource');
            Route::get('/traffic-stats', [\App\Http\Controllers\Admin\MikrotikController::class, 'trafficStats'])->name('traffic.stats');
            Route::get('/test-connection', [\App\Http\Controllers\Admin\MikrotikController::class, 'testConnection'])->name('test');
        });
        
        // CPE Management (GenieACS)
        Route::prefix('cpe')->name('cpe.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CpeController::class, 'index'])->name('index');
            Route::get('/{deviceId}', [\App\Http\Controllers\Admin\CpeController::class, 'show'])->name('show');
            Route::post('/{deviceId}/reboot', [\App\Http\Controllers\Admin\CpeController::class, 'reboot'])->name('reboot');
            Route::post('/{deviceId}/refresh', [\App\Http\Controllers\Admin\CpeController::class, 'refresh'])->name('refresh');
            Route::post('/{deviceId}/factory-reset', [\App\Http\Controllers\Admin\CpeController::class, 'factoryReset'])->name('factory-reset');
            Route::post('/{deviceId}/wifi', [\App\Http\Controllers\Admin\CpeController::class, 'updateWifi'])->name('wifi.update');
            Route::post('/bulk-reboot', [\App\Http\Controllers\Admin\CpeController::class, 'bulkReboot'])->name('bulk.reboot');
            Route::post('/bulk-refresh', [\App\Http\Controllers\Admin\CpeController::class, 'bulkRefresh'])->name('bulk.refresh');
        });
        
        // WhatsApp Gateway
        Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WhatsAppController::class, 'index'])->name('index');
            Route::get('/logs', [\App\Http\Controllers\Admin\WhatsAppController::class, 'logs'])->name('logs');
            Route::get('/test', [\App\Http\Controllers\Admin\WhatsAppController::class, 'test'])->name('test');
            Route::post('/test', [\App\Http\Controllers\Admin\WhatsAppController::class, 'sendTest'])->name('test.send');
            Route::post('/send', [\App\Http\Controllers\Admin\WhatsAppController::class, 'send'])->name('send');
            Route::get('/status', [\App\Http\Controllers\Admin\WhatsAppController::class, 'status'])->name('status');
            Route::post('/resend/{log}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'resend'])->name('resend');
            Route::post('/invoice/{invoice}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'sendInvoice'])->name('invoice');
            Route::post('/reminder/{invoice}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'sendReminder'])->name('reminder');
            Route::post('/bulk-invoice', [\App\Http\Controllers\Admin\WhatsAppController::class, 'bulkSendInvoice'])->name('bulk.invoice');
            Route::post('/bulk-reminder', [\App\Http\Controllers\Admin\WhatsAppController::class, 'bulkSendReminder'])->name('bulk.reminder');
        });
        
        // Payment Gateway
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
            Route::post('/create/{invoice}', [\App\Http\Controllers\Admin\PaymentController::class, 'createPayment'])->name('create');
            Route::get('/snap-token/{invoice}', [\App\Http\Controllers\Admin\PaymentController::class, 'getSnapToken'])->name('snap-token');
            Route::get('/check-status', [\App\Http\Controllers\Admin\PaymentController::class, 'checkStatus'])->name('check-status');
            Route::post('/send-link/{invoice}', [\App\Http\Controllers\Admin\PaymentController::class, 'sendPaymentLink'])->name('send-link');
        });
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
        });
        
        // Orders Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('show');
            Route::post('/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/confirm-payment', [\App\Http\Controllers\Admin\OrderController::class, 'confirmPayment'])->name('confirm-payment');
            Route::post('/{order}/complete', [\App\Http\Controllers\Admin\OrderController::class, 'complete'])->name('complete');
        });
    });
});

// Agent Routes
Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/login', function () {
        return view('agent.login');
    })->name('login');
    Route::post('/login', [\App\Http\Controllers\Portal\AgentController::class, 'login'])->name('login.post');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\AgentController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Portal\AgentController::class, 'logout'])->name('logout');
        Route::get('/vouchers/sell', [\App\Http\Controllers\Portal\AgentController::class, 'sellVoucher'])->name('vouchers.sell');
        Route::post('/vouchers/sell', [\App\Http\Controllers\Portal\AgentController::class, 'processSale'])->name('vouchers.process');
        Route::get('/topup', [\App\Http\Controllers\Portal\AgentController::class, 'topup'])->name('topup');
        Route::post('/topup', [\App\Http\Controllers\Portal\AgentController::class, 'processTopup'])->name('topup.process');
        Route::get('/transactions', [\App\Http\Controllers\Portal\AgentController::class, 'transactions'])->name('transactions');
        Route::get('/profile', [\App\Http\Controllers\Portal\AgentController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Portal\AgentController::class, 'updateProfile'])->name('profile.update');
    });
});

// Collector Routes
Route::prefix('collector')->name('collector.')->group(function () {
    Route::get('/login', function () {
        return view('collector.login');
    })->name('login');
    Route::post('/login', [\App\Http\Controllers\Portal\CollectorController::class, 'login'])->name('login.post');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\CollectorController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Portal\CollectorController::class, 'logout'])->name('logout');
        Route::get('/invoices', [\App\Http\Controllers\Portal\CollectorController::class, 'invoices'])->name('invoices');
        Route::get('/collect/{invoice?}', [\App\Http\Controllers\Portal\CollectorController::class, 'collect'])->name('collect');
        Route::post('/collect/{invoice}', [\App\Http\Controllers\Portal\CollectorController::class, 'processPayment'])->name('collect.process');
        Route::get('/history', [\App\Http\Controllers\Portal\CollectorController::class, 'history'])->name('history');
        Route::get('/profile', [\App\Http\Controllers\Portal\CollectorController::class, 'profile'])->name('profile');
    });
});

// Technician Routes
Route::prefix('technician')->name('technician.')->group(function () {
    Route::get('/login', function () {
        return view('technician.login');
    })->name('login');
    Route::post('/login', [\App\Http\Controllers\Portal\TechnicianController::class, 'login'])->name('login.post');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\TechnicianController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Portal\TechnicianController::class, 'logout'])->name('logout');
        Route::get('/tasks', [\App\Http\Controllers\Portal\TechnicianController::class, 'tasks'])->name('tasks');
        Route::get('/tasks/{task}', [\App\Http\Controllers\Portal\TechnicianController::class, 'showTask'])->name('tasks.show');
        Route::post('/tasks/{task}/update', [\App\Http\Controllers\Portal\TechnicianController::class, 'updateTask'])->name('tasks.update');
        Route::get('/installations', [\App\Http\Controllers\Portal\TechnicianController::class, 'installations'])->name('installations');
        Route::get('/repairs', [\App\Http\Controllers\Portal\TechnicianController::class, 'repairs'])->name('repairs');
        Route::get('/map', [\App\Http\Controllers\Portal\TechnicianController::class, 'map'])->name('map');
        Route::get('/profile', [\App\Http\Controllers\Portal\TechnicianController::class, 'profile'])->name('profile');
    });
});

// Customer Portal Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', function () {
        return view('customer.login');
    })->name('login');
    Route::post('/login', [\App\Http\Controllers\Portal\CustomerController::class, 'login'])->name('login.post');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\CustomerController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Portal\CustomerController::class, 'logout'])->name('logout');
        Route::get('/invoices', [\App\Http\Controllers\Portal\CustomerController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Portal\CustomerController::class, 'showInvoice'])->name('invoices.show');
        Route::get('/payments', [\App\Http\Controllers\Portal\CustomerController::class, 'payments'])->name('payments');
        Route::post('/pay/{invoice}', [\App\Http\Controllers\Portal\CustomerController::class, 'pay'])->name('pay');
        Route::get('/profile', [\App\Http\Controllers\Portal\CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Portal\CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::get('/support', [\App\Http\Controllers\Portal\CustomerController::class, 'support'])->name('support');
        Route::post('/support', [\App\Http\Controllers\Portal\CustomerController::class, 'submitTicket'])->name('support.submit');
    });
});

// Public Voucher Purchase
Route::prefix('voucher')->name('voucher.')->group(function () {
    Route::get('/buy', function () {
        return view('voucher.buy');
    })->name('buy');
    Route::post('/purchase', [VoucherController::class, 'purchase'])->name('purchase');
    Route::get('/success/{id}', [VoucherController::class, 'success'])->name('success');
});
