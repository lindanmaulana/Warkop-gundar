<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,superadmin,customer', 'not.suspended'])
    ->prefix('/setting')
    ->group(function () {
        Route::patch('/profile/{user}/update', [UserController::class, 'update'])->name('setting.profile.update');
        Route::patch("/profile/update/password", [AuthController::class, 'updatePassword'])->name('setting.profile.update.password');
    });


Route::middleware(['auth', 'not.suspended'])->prefix("/dashboard")->group(function() {
    
    Route::middleware(['role:admin,superadmin'])->group(function() {
        Route::get('/users', [UserController::class, 'index'])->name('dashboard.users');
    });

    Route::middleware(['role:superadmin'])->group(function() {
        Route::get("/users/update/{user}", [UserController::class, 'edit'])->name("dashboard.users.update");
        Route::patch("/users/update/{user}", [UserController::class, 'updateBySuperadmin'])->name("users.update");

        Route::patch("/users/suspendaccount/{user}", [UserController::class, 'suspendAccount'])->name('users.suspendaccount');
    });
});