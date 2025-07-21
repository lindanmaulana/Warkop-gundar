<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App; // Pastikan ini ada

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/v1/midtrans/callback', // PASTIKAN INI TETAP ADA DAN BENAR!
    ];

    public function handle($request, Closure $next)
    {
        // --- MULAI LOG DEBUGGING CSRF ---
        Log::info('--- DEBUGGING CSRF MIDDLEWARE ---');
        Log::info('Request Method: ' . $request->method());
        Log::info('Request Path (URL): ' . $request->path());
        Log::info('Excluded Paths ($except): ' . json_encode($this->except));

        $isInExcept = false;
        foreach ($this->except as $path) {
            if ($request->is($path)) { // ini yg dicek Laravel
                $isInExcept = true;
                Log::info('MATCHED EXCLUDED PATH: ' . $path);
                break; // Keluar dari loop setelah ketemu kecocokan
            }
        }
        Log::info('Is request path in $except array? ' . ($isInExcept ? 'TRUE' : 'FALSE'));

        Log::info('Is reading (GET/HEAD/OPTIONS)? ' . ($this->isReading($request) ? 'TRUE' : 'FALSE'));
        Log::info('Is running in console? ' . (App::runningInConsole() ? 'TRUE' : 'FALSE'));
        Log::info('Tokens match? ' . ($this->tokensMatch($request) ? 'TRUE' : 'FALSE'));
        Log::info('--- END DEBUGGING CSRF MIDDLEWARE ---');
        // --- AKHIR LOG DEBUGGING CSRF ---


        // Logika asli dari method handle Laravel
        if (
            $this->isReading($request) ||
            App::runningInConsole() ||
            $this->inExceptArray($request) || // Ini yang perlu jadi TRUE untuk rute callback
            $this->tokensMatch($request)
        ) {
            // Jika request masuk ke blok ini, berarti CSRF DIBYPASS atau valid.
            Log::info('CSRF Check: PASSED - Request will proceed to controller.');
            // KEMBALIKAN NEXT($REQUEST) SECARA LANGSUNG
            return $next($request);
        }

        // Jika request sampai di sini, berarti CSRF GAGAL (token mismatch)
        Log::warning('CSRF Check: FAILED - Token Mismatch Exception will be thrown. (Status 419)');
        throw new TokenMismatchException;
    }
}