<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Daftar route yang tidak memerlukan autentikasi
        $excludedRoutes = ['login', 'login.attempt'];

        // Dapatkan nama route saat ini
        $currentRoute = $request->route()?->getName();

        // Jika request adalah route yang dikecualikan, lanjutkan
        if (in_array($currentRoute, $excludedRoutes)) {
            return $next($request);
        }

        // Jika tidak ada token, redirect ke login
        if (!Session::has('token')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
