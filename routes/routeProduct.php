<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,customer'])
    ->prefix('/products')
    ->group(function () {
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::patch('/{product}/update', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
    });