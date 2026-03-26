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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api.editor.verify' => \App\Http\Middleware\VerifyUser::class,
            'api.client.verify' => \App\Http\Middleware\VerifyClient::class,
            'web.admin.verify' => \App\Http\Middleware\VerifyAdmin::class,
            'verify.authentication' => \App\Http\Middleware\CheckAuthentication::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
