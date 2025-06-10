<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinanceController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Admin Route
    Route::get('/merchants', [MerchantController::class, 'index'])->name('merchants.index');
    Route::get('/merchants/{id}', [MerchantController::class, 'show'])->name('merchants.show');
    Route::post('/merchants', [MerchantController::class, 'store'])->name('merchants.store');
    Route::post('/merchants/{id}/update', [MerchantController::class, 'update'])->name('merchants.update');
    Route::post('/merchants/{id}/delete', [MerchantController::class, 'destroy'])->name('merchants.delete');

    Route::get('/admin/product/{id}', [ProductController::class, 'index'])->name('admin.product.index');
    Route::post('/admin/product/{id}/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/admin/product/{id}/update', [ProductController::class, 'update'])->name('admin.product.update');

    Route::get('/admin/transactions/{id}', [TransactionController::class, 'index'])->name('admin.transaction.index');

    Route::get('finance/{id}', [FinanceController::class, 'index'])->name('admin.finance.show');
    Route::match(['GET', 'POST'], 'finance/create', [FinanceController::class, 'create'])->name('admin.finance.create');
    Route::match(['GET', 'PUT', 'PATCH'], 'finance/{id}/edit', [FinanceController::class, 'edit'])->name('admin.finance.edit');
    Route::delete('finance/{id}', [FinanceController::class, 'destroy'])->name('admin.finance.destroy');

    // Merchant Route
    Route::get('/merchant/product/{id}', [ProductController::class, 'merchantIndex'])->name('merchant.product.index');

    Route::get('/merchant/transactions/{id}', [TransactionController::class, 'merchantIndex'])->name('merchant.transaction.index');
    Route::match(['GET', 'POST'], '/merchant/transactions/create/{id}/{quantity}', [TransactionController::class, 'create'])->name('merchant.transactions.create');
});
