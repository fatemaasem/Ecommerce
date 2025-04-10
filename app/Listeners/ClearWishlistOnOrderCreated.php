<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Exceptions\CustomExceptions;
use App\Interfaces\WishlistRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ClearWishlistOnOrderCreated
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private WishlistRepositoryInterface $wishlistRepository
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        
        try {
            $userId = $event->order->user_id;
            $this->wishlistRepository->clearUserWishlist($userId);
            
            Log::info("Cleared wishlist for user {$userId} after order creation", [
                'order_id' => $event->order->id
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to clear wishlist for order {$event->order->id}", [
                'error' => $e->getMessage(),
                'user_id' => $event->order->user_id
            ]);
            
            
        }
    }
}
