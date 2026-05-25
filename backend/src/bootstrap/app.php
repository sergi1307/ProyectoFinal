<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Registrar con prefijo 'api' (para local y entornos donde no se elimina)
            Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Registrar sin prefijo (para producción donde Nginx elimina /api)
            Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*') || $request->is('auth/*') || str_contains($request->url(), '/api') || $request->is('registro')) {
                return response()->json([
                    'mensaje' => $e->getMessage(),
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }
        });
    })->create();
