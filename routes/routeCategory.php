<?php

use App\Http\Controllers\CategoryController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('/category')
    ->group(function() {
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::patch('/{category}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/{category}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
    });