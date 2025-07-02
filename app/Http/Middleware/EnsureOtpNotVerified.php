<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpNotVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('message', 'Anda belum login.');
        }

        if (Auth::user()->is_email_verified) {
            return redirect()->route('dashboard')->with('message', 'Akun Anda sudah diverifikasi.');
        }

        return $next($request);
    }
}
