<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json(['mensaje' => 'No autenticado'], 401);
                }
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                    return response()->json(['mensaje' => 'No autorizado'], 403);
                }
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
                    $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json(['mensaje' => 'Recurso no encontrado'], 404);
                }
            }
        });
    })->create();
