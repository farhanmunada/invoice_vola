<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    Route::post('customers/store-ajax', [App\Http\Controllers\CustomerController::class, 'storeAjax'])->name('customers.storeAjax');
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::get('invoices/{invoice}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('invoices/{invoice}/payment', [App\Http\Controllers\InvoiceController::class, 'addPayment'])->name('invoices.payment');
    Route::delete('payments/{payment}', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class);

    Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Hidden Feature: Dummy Invoice Generator
    Route::get('secret-invoice-generator', [App\Http\Controllers\DummyInvoiceController::class, 'create'])->name('dummy.create');
    Route::post('secret-invoice-generator', [App\Http\Controllers\DummyInvoiceController::class, 'store'])->name('dummy.store');
});


require __DIR__.'/auth.php';
