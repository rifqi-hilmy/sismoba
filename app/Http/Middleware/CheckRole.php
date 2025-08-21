<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $requiredRoles = explode(',', $roles);
        $userRoles = Session::get('roles', []);

        // Jika userRoles adalah string, ubah menjadi array
        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Periksa apakah ada peran yang cocok
        if (array_intersect($requiredRoles, $userRoles)) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
