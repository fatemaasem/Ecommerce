<?php

// app/DTOs/OrderDTO.php
namespace App\DTOs;

use App\Http\Requests\CreateOrderRequest;

class CreateOrderDTO
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public float $totalPrice,
        public string $status,
        public string $createdAt,
        public ?string $updatedAt
    ) {}

    
}