<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:customer'])
    ->group(function() {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/menu', [HomeController::class, 'showMenu'])->name('home.menu');
        Route::get('/cart', [HomeController::class, 'showCart'])->name('home.cart');
    });