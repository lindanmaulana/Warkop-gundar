<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::middleware(['role:admin'])->prefix('/dashboard/payments')->group(function() {
        Route::get('/', [PaymentController::class, 'index'])->name('dashboard.payments');
        Route::get('/create', [PaymentController::class, 'create'])->name('dashboard.payments.create');

        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
    });
});