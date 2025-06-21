<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanySettingsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceCloneController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceViewController;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'total_accounts' => Account::count(),
        'total_customers' => Customer::count(),
        'total_invoices' => Invoice::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('customers', CustomerController::class);

    Route::resource('invoices', InvoiceController::class);

    Route::get('invoices/{invoice}/clone', [InvoiceCloneController::class, 'create'])->name('invoices.clone');
    // Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::get('/company-settings', [CompanySettingsController::class, 'show'])->name('company-settings.show');
    Route::post('/company-settings', [CompanySettingsController::class, 'update'])->name('company-settings.update');

    Route::get('/invoices/{invoice}/preview', [InvoiceViewController::class, 'show'])->name('invoices.preview');
    Route::get('/invoices/{invoice}/download', [InvoiceViewController::class, 'download'])->name('invoices.download');

    Route::get('show-invoice', function() {
        return view('invoices.basic', ['invoice' => Invoice::find(1)->load(['items', 'account', 'company', 'customer'])]);
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
