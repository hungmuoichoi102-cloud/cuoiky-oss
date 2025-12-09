<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
            ], $this->getHttpStatusCode($exception));
        }

        return parent::render($request, $exception);
    }

    private function getHttpStatusCode($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
