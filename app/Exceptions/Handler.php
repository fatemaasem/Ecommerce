<?php

namespace App\Exceptions;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'error' => 'Authentication failed',
                'message' => 'Please provide a valid token',
                'code' => 401
            ], 401);
        }
        if ($exception instanceof TypeError) {
            return CustomExceptions::typeError($exception)->render($request);
        }
        if ($exception instanceof ValidationException) {
            return CustomExceptions::validationError($exception)->render($request);
        }
    
        if ($exception instanceof AuthenticationException) {
            return CustomExceptions::authenticationError($exception)->render($request);
        }
        if ($exception instanceof QueryException) {
            return CustomExceptions::queryError($exception)->render($request);
        }
      
        return parent::render($request, $exception);
    }


}
