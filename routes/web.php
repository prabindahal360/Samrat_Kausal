<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    
    Route::post('/customers/send-bulk-email', [CustomerController::class, 'sendBulkEmail'])
        ->name('customers.sendBulkEmail');
        Route::post('/customers/{customer}/send-email', [CustomerController::class, 'sendEmail'])
    ->name('customers.sendEmail');
    Route::get('/customers/import', [CustomerController::class, 'showImportForm'])->name('customers.import.form');
    Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::get('/customers/download-format', [CustomerController::class, 'downloadFormat'])
        ->name('customers.download.format');


    Route::get('/campaigns/{campaign}/logs', [CampaignController::class, 'logs'])->name('campaigns.logs');
    Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
    Route::resource('campaigns', CampaignController::class)->except(['show']);
    Route::get('/invoice-items', [InvoiceItemController::class, 'index'])->name('invoice-items.index');
    Route::resource('products', ProductController::class)->except(['show']);


    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    });
});

require __DIR__ . '/auth.php';
