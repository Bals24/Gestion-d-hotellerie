<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    // ✅ CONFIGURATION DES MIDDLEWARES
    ->withMiddleware(function (Middleware $middleware) {
        
        // 🔹 Middleware web group (OBLIGATOIRE pour Laravel 12)
        $middleware->web(append: [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,
            SubstituteBindings::class,
        ]);
        
        // 🔹 Middleware API (optionnel)
        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        
        // 🔹 Alias pour Spatie Permission (IMPORTANT)
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);
        
        // 🔹 Stateful APIs pour Sanctum (si utilisé)
        $middleware->statefulApi();
    })
    
    // ✅ GESTION DES EXCEPTIONS
    ->withExceptions(function (Exceptions $exceptions) {
        // Tu peux ajouter du logging personnalisé ici si besoin
    })
    
    ->create();