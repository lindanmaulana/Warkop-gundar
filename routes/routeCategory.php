<?php

use App\Http\Controllers\CategoryController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'not.suspended'])
    ->prefix('/category')
    ->group(function () {
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::patch('/{category}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/{category}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
    });


Route::middleware(['auth', 'not.suspended'])
    ->group(function () {
        Route::prefix('/dashboard')
            ->group(function () {
                Route::middleware(['role:admin,superadmin'])->group(function() {
                    Route::get('/categories', [CategoryController::class, 'index'])->name('dashboard.categories');
                });

                Route::middleware(['role:admin'])->group(function() {
                    Route::get('/categories/create', [CategoryController::class, 'create'])->name('dashboard.categories.create');
                    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('dashboard.categories.edit');
                });
            });

        Route::middleware(['role:admin'])->prefix('/categories')
            ->group(function () {
                Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
                Route::patch('/{category}/update', [CategoryController::class, 'update'])->name('categories.update');
                Route::delete('/{category}/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');
            });
    });