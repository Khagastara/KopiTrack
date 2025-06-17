<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinanceController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MerchantMiddleware;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'password'], function () {
    Route::get('/forgot', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend-otp');
    Route::get('/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset-form');
    Route::post('/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        // Dashboard - menggunakan DashboardController
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::resource('merchants', MerchantController::class);

        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

        Route::get('/admin/transactions/{id}', [TransactionController::class, 'index'])->name('admin.transaction.index');
        Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions.index');

        Route::get('/finance', [FinanceController::class, 'index'])->name('admin.finance.index');
        Route::match(['get', 'post'], '/finance/create', [FinanceController::class, 'create'])->name('admin.finance.create');
        Route::get('/finance/period', [FinanceController::class, 'getFinanceByPeriod'])->name('admin.finance.period');
        Route::get('/finance/{id}', [FinanceController::class, 'show'])->name('admin.finance.show');
        Route::match(['get', 'put', 'patch'], '/finance/{id}/edit', [FinanceController::class, 'edit'])->name('admin.finance.edit');
        Route::delete('/finance/{id}', [FinanceController::class, 'destroy'])->name('admin.finance.destroy');
        Route::post('/admin/finance/{id}/expenditure', [FinanceController::class, 'addExpenditureDetail'])->name('admin.finance.addExpenditureDetail');
        Route::delete('/admin/finance/expenditure/{id}', [FinanceController::class, 'removeExpenditureDetail'])
            ->name('admin.finance.removeExpenditureDetail');

        Route::get('/product/create/new', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('/product/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::get('/product/{id?}', [ProductController::class, 'index'])->name('admin.product.index');
    });

    // Merchant Routes
    Route::middleware([MerchantMiddleware::class])->group(function () {
        Route::get('/merchant/dashboard', function () {
            return view('merchant.dashboard');
        })->name('merchant.dashboard');

        Route::get('/merchant/dashboard', [DashboardController::class, 'merchantDashboard'])->name('merchant.dashboard');
        Route::get('/merchant/dashboard/{merchantId}', [DashboardController::class, 'merchantDashboard']);

        Route::get('/merchant/profile', [ProfileController::class, 'merchantIndex'])->name('merchant.profile.index');
        Route::get('/merchant/profile/edit', [ProfileController::class, 'merchantEdit'])->name('merchant.profile.edit');
        Route::put('/merchant/profile/update', [ProfileController::class, 'merchantUpdate'])->name('merchant.profile.update');

        Route::get('/merchant/product/{id?}', [ProductController::class, 'merchantIndex'])->name('merchant.product.index');

        Route::get('/merchant/transactions/{id}', [TransactionController::class, 'merchantIndex'])->name('merchant.transaction.index');
        Route::match(['GET', 'POST'], '/merchant/transactions/create/{id}/{quantity}', [TransactionController::class, 'create'])->name('merchant.transactions.create');

        Route::get('/merchant/transaction/create', [TransactionController::class, 'createForm'])
            ->name('merchant.transaction.create.form');
        Route::post('/merchant/transaction/cart/add', [TransactionController::class, 'addToCart'])
            ->name('merchant.transaction.cart.add');
        Route::delete('/merchant/transaction/cart/{product_id}', [TransactionController::class, 'removeFromCart'])
            ->name('merchant.transaction.cart.remove');
        Route::put('/merchant/transaction/cart/update', [TransactionController::class, 'updateCart'])
            ->name('merchant.transaction.cart.update');
        Route::post('/merchant/transaction/checkout', [TransactionController::class, 'checkout'])
            ->name('merchant.transaction.checkout');
        Route::delete('/merchant/transaction/cart', [TransactionController::class, 'clearCart'])
            ->name('merchant.transaction.cart.clear');
    });
});
