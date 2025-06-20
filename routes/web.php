<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware("guest")
    ->prefix('/auth')
    ->group(function () {
        Route::get("/login", [AuthController::class, 'showLoginForm'])->name('auth.login');
        Route::get("/register",[AuthController::class, 'showRegisterForm'])->name('auth.register');
        Route::post("/login", [AuthController::class, 'login'])->name('auth.login');
        Route::post("/register", [AuthController::class, 'register'])->name('auth.register');
    });

Route::post("/logout", [AuthController::class, 'logout'])->name('auth.logout');
    
Route::middleware(['auth', 'role:admin'])
    ->prefix('/dashboard/admin')
    ->group(function () {
        Route::get('/', fn () => 'Admin Dashboard');
    });

Route::middleware(['auth', 'role:admin,customer'])
    ->prefix('/dashboard')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/menu', [DashboardController::class, 'showDashboardMenu'])->name('dashboard.menu');
        Route::get('/menu/coffe', [DashboardController::class, 'showDashboardMenuCoffe'])->name('dashboard.menu.coffe');
});