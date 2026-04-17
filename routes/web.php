<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserPlatformController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'admin'])->group(function () {
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
    Route::get('/campaigns/{campaign}/preview', [CampaignController::class, 'preview'])->name('campaigns.preview');
    Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
    Route::resource('campaigns', CampaignController::class)->except(['show']);

    Route::get('/invoice-items', [InvoiceItemController::class, 'index'])->name('invoice-items.index');
    Route::resource('products', ProductController::class)->except(['show']);

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserPlatformController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/products', [UserPlatformController::class, 'products'])->name('user.products');
    Route::post('/user/products/{product}/buy', [UserPlatformController::class, 'buyProduct'])->name('user.products.buy');
    Route::get('/user/profile', [UserPlatformController::class, 'profile'])->name('user.profile');
});

require __DIR__ . '/auth.php';