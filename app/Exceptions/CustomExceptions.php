<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use TypeError;

class CustomExceptions extends Exception
{
    protected $statusCode;
    protected $message;

    public function __construct($message = "Something went wrong", $statusCode = 500)
    {
        // Allow $message to be either a string or an array
        parent::__construct(is_array($message) ? json_encode($message) : $message, $statusCode);
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }

    /**
     * Static methods for different exception types
     */
    public static function validationError(ValidationException $exception)
    {
        return new self($exception->errors(), 422);
    }
    public static function insufficientQuantityError($message="")
{
    // Convert the array to JSON string for the exception message
  

    return new self($message, 422);
}

    public static function authenticationError(AuthenticationException $exception,string $message="")
    {
        if($message)
        return  new self($message, 401);
        return new self("Authentication failed", 401);
    }

    public static function authorizationError($message ="Unauthorized access")
    {
        return new self($message, 403);
    }

    public static function notFoundError($message="Resource not found")
    {
        return new self($message, 404);
    }

    public static function queryError(QueryException $exception)
    {
        return new self("Database query error: " . $exception->getMessage(), 500);
    }

    public static function httpError(HttpException $exception)
    {
        return new self($exception->getMessage(), $exception->getStatusCode());
    }
    /**
     * Handle TypeError exceptions
     */
    public static function typeError(TypeError $exception)
    {
        return new self("Invalid input type: " . $exception->getMessage(), 400);
    }
}
