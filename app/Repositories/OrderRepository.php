<?php

namespace App\Repositories;

use App\DTOs\CreateOrderDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Exceptions\CustomExceptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(CreateOrderDTO $orderDTO, array $items): Order
    {
        return DB::transaction(function () use ($orderDTO, $items) {
            // Create order
            $order = Order::create([
                'user_id' => $orderDTO->userId,
                'total_price' => $orderDTO->totalPrice,
                'status' => $orderDTO->status
            ]);

            // Create order items
            $orderItems = [];
            foreach ($items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item["product_id"],
                    'quantity' => $item["quantity"],
                    'price' => $item["price"]
                ]);
                
            }
           
            return $order;
        });
    }

    public function getUserOrders(int $userId): LengthAwarePaginator
    {
        $orders = Order::with('items')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

       return $orders;
    }

    public function getOrderDetails(int $orderId, int $userId): ?Order
    {
        $order = Order::with('items')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        
        return $order;
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        
        $order->update(['status' => $status]);

        return $order;
    }

    public function getAllOrders(): LengthAwarePaginator
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

       return $orders;
    }

    public function getById(int $orderId): Order
{
    $order = Order::find($orderId);
    
    if (!$order) {
        throw CustomExceptions::notFoundError("Order with ID {$orderId} not found");
    }
    
    return $order;
}


}