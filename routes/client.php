<?php

use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientBillingController;
use App\Http\Controllers\ClientPaymentHistoryController;
use App\Http\Controllers\ClientSettingsController;
use Illuminate\Support\Facades\Route;

// Rute yang tidak memerlukan login
Route::get('login', [ClientAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [ClientAuthController::class, 'store'])->name('login.store');
Route::post('logout', [ClientAuthController::class, 'logout'])->name('logout');

// Rute yang memerlukan login klien
Route::middleware('auth.client')->group(function () {
    Route::get('dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('tagihan', [ClientBillingController::class, 'index'])->name('billing');
    Route::get('riwayat-pembayaran', [ClientPaymentHistoryController::class, 'index'])->name('payment-history');
    Route::get('pengaturan', [ClientSettingsController::class, 'index'])->name('settings');
    Route::put('pengaturan', [ClientSettingsController::class, 'update'])->name('settings.update');
    Route::get('bayar/{id}', [ClientBillingController::class, 'bayar'])->name('bayar');
    Route::get('invoice/{id}', [ClientBillingController::class, 'invoice'])->name('invoice');
});