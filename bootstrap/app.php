<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\RedirectResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'tokenauth' => \App\Http\Middleware\TokenAuth::class,
            'password.expired' => \App\Http\Middleware\CheckPasswordExpired::class,
            'session.expired' => \App\Http\Middleware\expiredSession::class,
            'check.payroll' => \App\Http\Middleware\PermissionPage::class,
            'check.data-karyawan' => \App\Http\Middleware\PermissionPage::class,
            'check.jadwal-shift' => \App\Http\Middleware\PermissionPage::class,
            'check.manage-user' => \App\Http\Middleware\PermissionPage::class,
            'check.dashboard-view' => \App\Http\Middleware\PermissionPage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->renderable(function (TokenMismatchException $e, $request) {
        //     return response()->view('errors.419', [], 419);
        // });
    })->create();
