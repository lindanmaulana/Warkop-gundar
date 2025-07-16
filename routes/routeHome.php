<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentProofsController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:customer'])
    ->group(function() {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/menu', [HomeController::class, 'showMenu'])->name('home.menu');
        Route::get('/profile', [HomeController::class, 'showProfile'])->name('home.profile');
        
        Route::middleware('otp.verified')->group(function() {
            Route::get('/cart', [HomeController::class, 'showCart'])->name('home.cart');
            Route::get('/checkout', [HomeController::class, 'showCheckout'])->name('home.checkout');
            Route::get('/order', [HomeController::class, 'showOrder'])->name('home.order');
            Route::get('/order/{order}/detail', [HomeController::class, 'showDetailOrder'])->name('home.order.detail');
            Route::get('/order/{order}/payment', [HomeController::class, 'showPayment'])->name('home.order.payment');
    
            Route::patch('/profile/{user}/update', [HomeController::class, 'updateProfile'])->name('profile.update');
            Route::post('/checkout', [HomeController::class, 'createOrder'])->name('checkout');
            Route::post('/order/{order}/cancel', [HomeController::class, 'cancelOrder'])->name('order.cancel');
            Route::post('/upload/{order}/payment', [PaymentProofsController::class, 'store'])->name('upload.payment');
        });
    });