<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('mobile*')) {
                return route('mobile.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $exception, \Illuminate\Http\Request $request) {
            if (! $request->isMethod('POST') || ! $request->is('spmb')) {
                return null;
            }
            if ($exception instanceof \Illuminate\Validation\ValidationException
                || ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                    && $exception->getStatusCode() < 500)) {
                return null;
            }

            return \App\Support\SpmbSubmissionError::response($exception);
        });
    })->create();
