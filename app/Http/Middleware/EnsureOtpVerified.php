<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Anda belum login.');
        }

        if (!Auth::user()->is_email_verified) {
            switch (Auth::user()->role) {
                case UserRole::Admin:
                    return redirect()->route('dashboard')->with('error', 'Akun Anda belum terverifikasi.');
                    break;
                case UserRole::Customer:
                    return redirect()->route('home')->with('error', 'Akun Anda belum terverifikasi.');
                    break;
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('dashboard')->with('error', 'Akun Anda belum terverifikasi.');
            }
        }

        return $next($request);
    }
}
