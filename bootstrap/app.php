<?php

use App\Exceptions\BadRequestException;
use App\Exceptions\ValidationErrorException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $th) {
            if ($th instanceof BadRequestException) {
                return response()->json([
                    'message' => $th->getMessage(),
                ], HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            if ($th instanceof ValidationErrorException) {
                return response()->json([
                    'message' => json_decode($th->getMessage()),
                ], HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'message' => $th->getMessage(),
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
