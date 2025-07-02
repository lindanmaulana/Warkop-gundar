<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,customer'])
    ->prefix('/setting')
    ->group(function () {
        Route::patch('/profile/{user}/update', [UserController::class, 'update'])->name('setting.profile.update');
    });