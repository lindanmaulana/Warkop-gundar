<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::prefix("/api/v1")->group(function () {
    Route::middleware(['auth', 'not.suspended'])->group(function () {
        Route::middleware(["role:superadmin"])->group(function() {
            Route::patch("/users/suspended/{user}", [UserController::class, 'suspendAccount']);
        });

        Route::middleware('role:admin,superadmin')->group(function () {
            Route::get('/users', [UserController::class, 'getAllUser']);
        });

        Route::prefix('/products')->group(function () {
            Route::get("/", [ProductController::class, 'getAllProduct']);
        });

        Route::prefix("/categories")->group(function () {
            Route::get("/", [CategoryController::class, 'getAllCategory']);
        });

        Route::prefix("/orders")->group(function () {
            Route::get("/", [OrderController::class, 'getAllOrder']);
        });

        Route::prefix("/transactions")->group(function() {
            Route::get("/", [TransactionController::class, 'getAllTransaction']);
            Route::post("/snap", [TransactionController::class, 'getSnapToken']);
        });
    });

    // callback midtrans
    Route::prefix("/midtrans")->group(function() {
        Route::post("/callback", [MidtransController::class, 'handleMidtransCallback']);
    });

    Route::get("/menus", [HomeController::class, 'getAllMenu']);
});
