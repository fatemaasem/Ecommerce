<?php

namespace App\Traits;

use App\Services\CartService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
trait ApiResponses
{

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection], $code);
    }

    protected static function successResponse(array $params = [], int $code = 200): JsonResponse
{
    $response = response()->json([
        'data' => $params['data'] ?? null,
        'code' => $code,
        'message' => $params['message'] ?? 'Success',
        'count' => $params['count'] ?? 0,
    ], $code);

    // Add each cookie to the response
  
    $response->withCookie(cookie('session_id',app(CartService::class)->getCartId() ) );

    return $response;
}


    protected function showOne(Model $model, $code = 200)
    {
        return $this->successResponse(['data' => $model], $code);
    }
}

?>
