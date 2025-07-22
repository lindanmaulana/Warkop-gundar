<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,customer', 'not.suspended'])
    ->prefix('/products')
    ->group(function () {
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::patch('/{product}/update', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
    });


Route::middleware(['auth', 'not.suspended'])
    ->group(function () {
        Route::prefix('/dashboard')
            ->group(function () {
                Route::middleware(['role:admin'])->group(function () {
                    Route::get('/menu/products', [ProductController::class, 'index'])->name('dashboard.menu.products');
                    Route::get('/menu/products/create', [ProductController::class, 'create'])->name('dashboard.menu.products.create');
                    Route::get('/menu/products/{product}/edit', [ProductController::class, 'edit'])->name('dashboard.menu.products.edit');
                    Route::get('/menu/products/{product}/detail', [ProductController::class, 'show'])->name('dashboard.menu.products.detail');
                    Route::get('/menu/{categoryId}/products/list', [ProductController::class, 'getByCategory'])->name('dashboard.menu.products.list');
                });
            });

        Route::middleware(['role:admin'])
            ->prefix('/products')
            ->group(function () {
                Route::post('/store', [ProductController::class, 'store'])->name('products.store');
                Route::patch('/{product}/update', [ProductController::class, 'update'])->name('products.update');
                Route::delete('/{product}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
            });
    });
