<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/auth/login');
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'otp.not.verified' => \App\Http\Middleware\EnsureOtpNotVerified::class,
            'otp.verified' => \App\Http\Middleware\EnsureOtpVerified::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
    })->create();
