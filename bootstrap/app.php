<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if ($exception->getStatusCode() !== 419) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session expired. Please log in again.',
                ], 419);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->fullUrl();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($previousUrl && $previousUrl !== $currentUrl) {
                    $request->session()->put('url.intended', $previousUrl);
                }
            }

            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            return redirect()
                ->route('login')
                ->with('warning', 'Your session expired. Please log in again.');
        });
    })->create();
