<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OrganizerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (\Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'manager' => \App\Http\Middleware\ManagerMiddleware::class,
        'organizer' => \App\Http\Middleware\OrganizerMiddleware::class,
        'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class
    ]);
    
    // Global middleware - check maintenance mode for all web requests
    $middleware->web(append: [
        \App\Http\Middleware\CheckMaintenanceMode::class,
    ]);
    
    // Exclude SSLCommerz callback routes from CSRF verification
    $middleware->validateCsrfTokens(except: [
        'payment/success',
        'payment/fail', 
        'payment/cancel',
        'payment/ipn',
    ]);
})

    ->withExceptions(function ($exceptions) {
        // Customize exception handling here if needed.
    })
    ->create();
