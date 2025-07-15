<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix("/api/v1")->group(function() {
    Route::prefix('/products')->group(function() {
        Route::get("/", [ProductController::class, 'getAllProduct']);
    });

    Route::prefix("/categories")->group(function() {
        Route::get("/", [CategoryController::class, 'getAllCategory']);
    });
});