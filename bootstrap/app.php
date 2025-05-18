<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Pastikan middleware 'auth' sudah terdaftar dan diaktifkan untuk pengecekan autentikasi
        // Ini biasanya otomatis terdaftar oleh Laravel
        // Middleware Authenticate akan melakukan redirect ke halaman login jika pengguna belum login
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();