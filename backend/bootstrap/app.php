<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

    })->withExceptions(function (Exceptions $exceptions): void {
            $exceptions->render(function (NotFoundHttpException $e, Request $request) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'error' => 'O cliente não foi encontrado (ID inexistente).'
                    ], 404);
                }
            });
    })->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Você precisa estar autenticado para acessar esta funcionalidade.',
                'error' => 'Não autorizado'
            ], 401);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (DomainException $e, Request $request) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 422);
    });

        //
    })->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (Throwable $e, Request $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Ocorreu um erro interno. Tente novamente mais tarde.'
                ], 500);
            }

            return null;
        });

    })->create();
