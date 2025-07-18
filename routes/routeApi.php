<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix("/api/v1")->group(function() {

    Route::middleware('role:admin')->group(function() {
        Route::get('/users', [UserController::class, 'getAllUser']);
    });


    Route::prefix('/products')->group(function() {
        Route::get("/", [ProductController::class, 'getAllProduct']);
    });

    Route::prefix("/categories")->group(function() {
        Route::get("/", [CategoryController::class, 'getAllCategory']);
    });
    
    Route::prefix("/orders")->group(function() {
        Route::get("/", [OrderController::class, 'getAllOrder']); 
    });

    //customer
    Route::get("/menus", [HomeController::class, 'getAllMenu']);
});