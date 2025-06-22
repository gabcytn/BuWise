<?php

use App\Http\Middleware\CheckUserSuspendedStatus;
use App\Http\Middleware\EnableMFA;
use App\Http\Middleware\Onboarding;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verify.api' => VerifyApiKey::class,
            'enable.mfa' => EnableMFA::class,
            'onboarding' => Onboarding::class,
            'suspended' => CheckUserSuspendedStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
