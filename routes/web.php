<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
require __DIR__ . '//routeCategory.php'; 
require __DIR__ . '//routeProduct.php'; 


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
        Route::get("/category", [CategoryController::class, 'index'])->name('dashboard.admin.category');
        Route::get("/category/create", [CategoryController::class, 'create'])->name('dashboard.admin.category.create');
        Route::get('/category/{category}/update', [CategoryController::class, 'edit'])->name('dashboard.admin.category.update');
    });

Route::middleware(['auth', 'role:admin,customer'])
    ->prefix('/dashboard')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/menu', [DashboardController::class, 'showDashboardMenu'])->name('dashboard.menu');
        Route::get('/menu/{categoryId}/product', [ProductController::class, 'index'])->name('dashboard.menu.product');
        Route::get('/menu/product/create', [ProductController::class, 'create'])->name('dashboard.menu.product.create');
        Route::get('/menu/product/{product}/update', [ProductController::class, 'edit'])->name('dashboard.menu.product.update');
});