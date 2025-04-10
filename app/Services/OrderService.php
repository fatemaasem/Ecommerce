<?php

// app/Services/OrderService.php
namespace App\Services;

use App\DTOs\CreateOrderDTO;
use App\DTOs\OrderItemDTO;
use App\Events\OrderCreated;

use App\Exceptions\CustomExceptions;
use App\Interfaces\OrderRepositoryInterface ;
use App\Interfaces\ProductRepositoryInterface ;
use App\Interfaces\WishlistRepositoryInterface ;
use App\Mappers\OrderMapper;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
        private WishlistRepositoryInterface $wishlistRepository
    ) {}

    public function createOrderFromWishlist(int $userId): array
    {
        return DB::transaction(function () use ($userId) {
            // Get wishlist items
            $wishlistItems = $this->wishlistRepository->getUserWishlistWithoutPaginate($userId);
            
            if ($wishlistItems->isEmpty()) {
                throw CustomExceptions::notFoundError('No items in wishlist to create order');
            }

            // Validate quantities and calculate total
            $totalPrice = 0;
            $orderItems = [];
            
            foreach ($wishlistItems as $item) {
                $product = $this->productRepository->getProductQuantityAndPrice($item->product_id);
                
                
                $totalPrice += $product->discounted_price * $item->quantity;
                $orderItems[] = [
                   "product_id"=> $item->product_id,
                    "quantity"=>$item->quantity,
                    "price"=>$product->discounted_price
                ];
            }

            // Create order DTO
            $orderDTO = new CreateOrderDTO(
                null,
                $userId,
                $totalPrice,
                'Pending',
                now()->toDateTimeString(),
                null
            );

            // Create order
            $createdOrder = $this->orderRepository->createOrder($orderDTO, $orderItems);
           
            // Clear wishlist
            $this->wishlistRepository->clearUserWishlist($userId);
          
            // Dispatch event
            event(new OrderCreated($createdOrder));
           
            return OrderMapper::toResponse($createdOrder);
        });
    }

    public function getUserOrders(int $userId): array
    {
       
        return OrderMapper::toDTOCollection($this->orderRepository->getUserOrders($userId)) ;
    }

    public function getOrderDetails(int $orderId, int $userId): array
    {
        $order = $this->orderRepository->getOrderDetails($orderId, $userId);
        
        if (!$order) {
            throw CustomExceptions::notFoundError('Order not found');
        }

        return OrderMapper::toResponse($order);
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $order =$this->orderRepository->getById($orderId);
        $this->orderRepository->updateOrderStatus($order, $status);
        return true;
    }

    public function getAllOrders(): array
    {
        return OrderMapper::toDTOCollection($this->orderRepository->getAllOrders());
    }
}