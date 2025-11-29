<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('attendance:auto-checkout')
            ->dailyAt('00:30')
            ->timezone('Asia/Jakarta')
            ->onSuccess(function () {
                \Log::info('Auto checkout completed successfully');
            })
            ->onFailure(function () {
                \Log::error('Auto checkout failed');
            });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
