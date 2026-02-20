<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware globaux de sécurité
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Inertia middleware
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        
        // Protection contre les attaques par force brute sur les routes d'authentification
        $middleware->alias([
            'api.auth' => \App\Http\Middleware\ApiTokenAuth::class,
            'check.project.limit' => \App\Http\Middleware\CheckProjectLimit::class,
            'check.team.limit' => \App\Http\Middleware\CheckTeamLimit::class,
            'check.task.permission' => \App\Http\Middleware\CheckTaskPermission::class,
            'throttle.login' => \App\Http\Middleware\ThrottleLoginAttempts::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'slack/commands',
            'webhook/stripe',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
