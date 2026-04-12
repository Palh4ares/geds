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
        // Registra alias de middleware personalizado
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Redireciona para login quando não autenticado
        $middleware->redirectGuestsTo(fn() => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Página customizada para 403
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }
        });
    })->create();
