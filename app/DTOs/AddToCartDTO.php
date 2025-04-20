<?php
// app/DTOs/WishlistDTO.php

namespace App\DTOs;

use App\Http\Requests\AddToCartRequest;
use Illuminate\Support\Facades\Auth;

class AddToCartDTO
{
    public function __construct(

        public readonly ?int $userId,
        public readonly int $productId,
        public readonly ?int $quantity,
    
    ) {}

    public static function fromRequest(AddToCartRequest $request): self
    {
        return new self(
            userId: Auth::user()?->id,
            productId: $request->product_id,
            quantity: $request->quantity ?? 1 // Default to 1 if null
        );
    }
}