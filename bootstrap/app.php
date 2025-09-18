<?php

use App\Exceptions\RepositoryException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (RepositoryException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Repository Error',
                    'message' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Resource not found',
                ], 404);
            }
        });

        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });


        $exceptions->render(function (AuthorizationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => $e->getMessage(),
                ], 403);
            }
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => $e->getMessage(),
                ], 401);
            }
        });

        $exceptions->render(function (QueryException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Database error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        });

        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Too many requests',
                    'message' => $e->getMessage(),
                ], 429);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Resource not found',
                ], 404);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Internal Server Error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        });
    })->create();
