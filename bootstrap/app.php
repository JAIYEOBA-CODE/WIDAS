<?php

use App\Http\Middleware\CheckBlockedIp;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\IntrusionDetection;
use App\Http\Middleware\LogActivity;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AuthServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            CheckBlockedIp::class,
            HandleInertiaRequests::class,
            IntrusionDetection::class,
            LogActivity::class,
        ]);

        $middleware->api(append: [
            CheckBlockedIp::class,
            'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
