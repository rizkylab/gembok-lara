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

// Public Routes
Route::get('/', function () {
    return redirect('/admin/login');
});

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
        
        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

// Agent Routes
Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/login', function () {
        return view('agent.login');
    })->name('login');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('agent.dashboard');
        })->name('dashboard');
    });
});

// Collector Routes
Route::prefix('collector')->name('collector.')->group(function () {
    Route::get('/login', function () {
        return view('collector.login');
    })->name('login');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('collector.dashboard');
        })->name('dashboard');
    });
});

// Technician Routes
Route::prefix('technician')->name('technician.')->group(function () {
    Route::get('/login', function () {
        return view('technician.login');
    })->name('login');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('technician.dashboard');
        })->name('dashboard');
    });
});

// Customer Portal Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', function () {
        return view('customer.login');
    })->name('login');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('dashboard');
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
