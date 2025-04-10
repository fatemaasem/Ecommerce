<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Exceptions\CustomExceptions;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantity
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function handle(OrderCreated $event)
    {
       
        
        foreach ($event->order->items as $item) {
            $this->productRepository->decrementQuantity(
                $item->product_id,
                $item->quantity
            );
           
        }
       
    }
}
