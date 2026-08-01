<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CashPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/password/change', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/password/change', [LoginController::class, 'changePassword'])->name('password.change');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students - full CRUD gated by StudentPolicy inside the controller (authorizeResource)
    Route::resource('students', StudentController::class)->except(['show', 'create', 'edit']);

    // Cash payments (weekly kas)
    Route::get('/cash-payments', [CashPaymentController::class, 'index'])->name('cash-payments.index');
    Route::get('/cash-payments/mine', [CashPaymentController::class, 'mine'])->name('cash-payments.mine');
    Route::post('/cash-payments/{student}/paid', [CashPaymentController::class, 'markPaid'])->name('cash-payments.mark-paid');
    Route::post('/cash-payments/{student}/unpaid', [CashPaymentController::class, 'markUnpaid'])->name('cash-payments.mark-unpaid');
    Route::delete('/cash-payments/{cashPayment}', [CashPaymentController::class, 'destroy'])->name('cash-payments.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');

    // Activity log - treasurer only (also enforced via policy inside controller)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('role:treasurer')
        ->name('activity-logs.index');

    // Settings - treasurer only
    Route::middleware('role:treasurer')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/logo', [SettingController::class, 'uploadLogo'])->name('settings.logo');
    });
});
