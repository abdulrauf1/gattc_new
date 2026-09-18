<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | GATTC Custom Middleware + Spatie Permission
        |--------------------------------------------------------------------------
        |
        | Use fully-qualified class names here.
        | This avoids Laravel resolving "PermissionMiddleware"
        | as a non-existent global class.
        |
        */

        $middleware->alias([

            /*
            |--------------------------------------------------------------------------
            | Active User
            |--------------------------------------------------------------------------
            */

            'active.user' =>
                \App\Http\Middleware\EnsureUserIsActive::class,


            /*
            |--------------------------------------------------------------------------
            | Spatie Permission Middleware
            |--------------------------------------------------------------------------
            */

            'role' =>
                \Spatie\Permission\Middleware\RoleMiddleware::class,

            'permission' =>
                \Spatie\Permission\Middleware\PermissionMiddleware::class,

            'role_or_permission' =>
                \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();