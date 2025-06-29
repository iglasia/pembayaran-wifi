<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientBillingController;
use App\Http\Controllers\ClientPaymentController;
use App\Http\Controllers\ClientPaymentHistoryController;
use App\Http\Controllers\ClientSettingsController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InternetPackageController;
use App\Http\Controllers\AdministratorApplicationController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\ExportRecapController;
use App\Http\Controllers\ExportDuesController;
use App\Http\Controllers\SettingController;

// -----------------------------
// REDIRECT ROOT
// -----------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// -----------------------------
// ROUTE CLIENT
// -----------------------------
Route::prefix('client')->name('client.')->group(function () {
    // tanpa login
    Route::get('login', [ClientAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ClientAuthController::class, 'store'])->name('login.store');
    Route::post('logout', [ClientAuthController::class, 'logout'])->name('logout');

    // dengan login
    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('tagihan', [ClientBillingController::class, 'index'])->name('billing');
        Route::get('riwayat-pembayaran', [ClientPaymentHistoryController::class, 'index'])->name('payment-history');
        Route::get('pengaturan', [ClientSettingsController::class, 'index'])->name('settings');
        Route::put('pengaturan', [ClientSettingsController::class, 'update'])->name('settings.update');
        Route::get('bayar/{id}', [ClientBillingController::class, 'bayar'])->name('bayar');
        Route::get('invoice/{id}', [ClientBillingController::class, 'invoice'])->name('invoice');
        Route::post('pembayaran', [ClientPaymentController::class, 'token'])->name('pembayaran');
        Route::get('pembayaran/{id}/{orderId}', [ClientPaymentController::class, 'pembayaranSukses'])->name('pembayaran-sukses');
    });
});

// -----------------------------
// ROUTE ADMIN (WEB UTAMA)
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('klien', ClientController::class);

    Route::resource('tagihan', TransactionController::class)->except(['create', 'edit', 'show']);

    Route::resource('paket-internet', InternetPackageController::class)->except(['create', 'show', 'edit']);

    Route::resource('administrator-aplikasi', AdministratorApplicationController::class)->except(['create', ' show', 'edit']);

    Route::resource('payment-history', PaymentHistoryController::class)->only(['index', 'show']);

    Route::get('invoice/{transaction}', [InvoiceController::class, 'show'])->name('invoice.show');

    Route::name('laporan.')->prefix('laporan')->group(function () {
        Route::get('rekap', [TransactionReportController::class, 'index'])->name('rekap.index');
        Route::get('/export/rekap/{year}', ExportRecapController::class)->name('export.recap');
        Route::get('/export/rekap/iuran/{year}', ExportDuesController::class)->name('export.dues');
    });

    Route::put('/klien/{id}/toggle-subscription', [ClientController::class, 'toggleSubscription'])->name('klien.toggle-subscription');

    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
});

// -----------------------------
// AUTHENTICATION
// -----------------------------
require __DIR__ . '/auth.php';

// -----------------------------
// FALLBACK
// -----------------------------
Route::fallback(function () {
    abort(404);
});
