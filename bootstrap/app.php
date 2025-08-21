<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureUserIsLoggedIn;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware global
        $middleware->web([
            EnsureUserIsLoggedIn::class,
        ]);

        // Daftarkan middleware alias
        $middleware->alias([
            'role' => CheckRole::class,
            'ensureLogin' => EnsureUserIsLoggedIn::class,
        ]);

        // Tambahkan pengecualian CSRF untuk login jika diperlukan
        $middleware->validateCsrfTokens(except: [
            'login.attempt',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
