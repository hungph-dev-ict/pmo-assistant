<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ShareProjectManagerData;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetLocaleByIp;
use App\Http\Middleware\LogRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'redirect.task' => \App\Http\Middleware\RedirectTaskRoute::class, // Đăng ký middleware
        ]);
        $middleware->web(append: [
            ShareProjectManagerData::class,
            SetLocale::class,
            // SetLocaleByIp::class,
            LogRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
