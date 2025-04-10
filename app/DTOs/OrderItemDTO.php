<?php
// app/DTOs/OrderItemDTO.php

namespace App\DTOs;

class OrderItemDTO
{
    public function __construct(
     
        public int $orderId,
        public int $productId,
        public int $quantity,
        public float $price,
  
    ) {}

   

    
}