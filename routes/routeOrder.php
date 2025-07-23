<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not.suspended'])
    ->prefix('/order')
    ->group(function () {
        Route::middleware('role:customer')
            ->group(function () {
                Route::post('/checkout', [OrderController::class, 'store'])->name('order.checkout');
            });
        Route::middleware('role:admin')
            ->group(function () {
                Route::patch('/{order}/update', [OrderController::class, 'update'])->name('order.update');
            });
    });


Route::middleware(['auth', 'not.suspended'])
    ->group(function () {

        Route::prefix('/dashboard')->group(function () {
            Route::middleware(["role:admin,superadmin"])->group(function() {
                Route::get('/orders', [OrderController::class, 'index'])->name('dashboard.orders');
                Route::get('/orders/{order}/detail', [OrderController::class, 'getDetailOrder'])->name('dashboard.orders.detail');
            });

            Route::middleware(['role:admin'])->group(function () {
                Route::get('/orders/{order}/update', [OrderController::class, 'edit'])->name('dashboard.orders.update');
                Route::get('/orders/cart', [OrderController::class, 'showOrderCart'])->name('dashboard.orders.cart');
                Route::get('/orders/checkout', [OrderController::class, 'showOrderCheckout'])->name('dashboard.orders.checkout');
            });
        });

        Route::prefix('/orders')->group(function () {
            Route::middleware('role:customer')
                ->group(function () {
                    Route::post('/checkout', [OrderController::class, 'store'])->name('orders.checkout');
                });

            Route::middleware('role:admin')
                ->group(function () {
                    Route::patch('/{order}/update', [OrderController::class, 'update'])->name('orders.update');
                });
        });
    });