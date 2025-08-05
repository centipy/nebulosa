<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule; // <-- 1. Importa la clase Schedule

return Application::configure(basePath: dirname(__DIR__))
     ->withRouting(
          web: __DIR__ . '/../routes/web.php',
          commands: __DIR__ . '/../routes/console.php',
          health: '/up',
     )
     ->withMiddleware(function (Middleware $middleware): void {
          $middleware->web(append: [
               \App\Http\Middleware\HandleInertiaRequests::class,
               \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
          ]);

          //
     })
     ->withExceptions(function (Exceptions $exceptions): void {
          //
     })
     // --- 2. AÑADE ESTE BLOQUE PARA LAS TAREAS PROGRAMADAS ---
     ->withSchedule(function (Schedule $schedule) {
          $schedule->command('notifications:send-birthday-reminders')->daily();
     })
     // ----------------------------------------------------
     ->create();
