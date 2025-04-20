<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AddToCartDTO;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;
use Stripe\ApiResource;

class CartController extends ApiController
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(AddToCartRequest $request)
    {
        $cartDto=AddToCartDTO::fromRequest($request);
       
       $cartModel= $this->cartService->addProduct($cartDto);
        return ApiController::successResponse([
            "data"=>new CartResource($cartModel),
            "message"=>"Cart Created "
        ]);

       
    }
}
