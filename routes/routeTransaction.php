<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,superadmin', 'not.suspended'])
    ->prefix('/dashboard/transactions')
    ->group(function () {
        Route::get("/", [TransactionController::class, 'index'])->name("dashboard.transactions");
        Route::get("/{transaction}/detail", [TransactionController::class, 'show'])->name("dashboard.transactions.detail");
    });
