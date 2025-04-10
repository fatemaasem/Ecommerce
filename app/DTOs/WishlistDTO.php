<?php
// app/DTOs/WishlistDTO.php

namespace App\DTOs;

class WishlistDTO
{
    public function __construct(

        public readonly int $userId,
        public readonly int $productId,
        public readonly ?int $quantity,
    
    ) {}

    public static function fromRequest(int $userId, int $productId, ?int $quantity = null): self
    {
        return new self(
            userId: $userId,
            productId: $productId,
            quantity: $quantity ?? 1 // Default to 1 if null
        );
    }
}