<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class InvalidToken extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Bisa log ke external service jika ingin
        });

        $this->renderable(function (Throwable $e, $request) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada sistem.',
                'error' => $e->getMessage(), // ⚠️ hanya tampilkan saat debug true
            ], 500);
        });
    }
}
