<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanySettingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('accounts', AccountController::class);

    Route::get('/company-settings', [CompanySettingsController::class, 'show'])->name('company-settings.show');
    Route::post('/company-settings', [CompanySettingsController::class, 'update'])->name('company-settings.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
