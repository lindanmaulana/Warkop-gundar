<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

require __DIR__ . '//routeCategory.php';
require __DIR__ . '//routeProduct.php';
require __DIR__ . '//routeUser.php';
require __DIR__ . '//routeOrder.php';
require __DIR__ . '//routePayment.php';
require __DIR__ . '//routeHome.php';

Route::get('/dev/otp', function () {
    return view('components/');
});

Route::middleware("guest")
    ->prefix('/auth')
    ->group(function () {
        Route::get("/login", [AuthController::class, 'showLoginForm'])->name('auth.login');
        Route::get("/register", [AuthController::class, 'showRegisterForm'])->name('auth.register');
        Route::post("/login", [AuthController::class, 'login'])->name('auth.login');
        Route::post("/register", [AuthController::class, 'register'])->name('auth.register');
    });

Route::middleware(['auth', 'otp.not.verified'])->group(function () {
    Route::get("/auth/otp", [AuthController::class, 'showOtpForm'])->name('auth.otp');
    Route::post("/otp", [AuthController::class, 'otpVerified'])->name('otp');
});

Route::post("/logout", [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:admin')->group(function () {
            Route::get('/dashboard/setting', [DashboardController::class, 'showDashboardSetting'])->name('dashboard.setting');
        });
    });