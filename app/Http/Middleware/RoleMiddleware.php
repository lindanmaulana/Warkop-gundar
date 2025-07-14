<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role->value ?? $user->role, $roles)) {
            // abort(403, 'Unauthorized');

            return redirect()->back()->with('error', 'Kamu tidak di izinkan untuk mengakses halaman yang di tuju.');
        }

        return $next($request);
    }
}