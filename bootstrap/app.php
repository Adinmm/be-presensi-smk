<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('v1')
                ->middleware('api')
                ->group(function () {
                    require base_path('routes/auth.php');
                });

            Route::prefix('v1')
                ->middleware(['api', 'auth:sanctum'])
                ->group(function () {
                    require base_path('routes/student.php');
                    require base_path('routes/kelas.php');
                    require base_path('routes/attendence.php');
                    require base_path('routes/report.php');
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->encryptCookies(except: [
        'token', 
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->render(function (\Symfony\Component\Routing\Exception\RouteNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated. Token tidak ditemukan atau tidak valid.',
                    'error' => 'Unauthorized'
                ], 401);
            }
        });
    })
    ->create();
