<?php


namespace App\Interfaces;

use App\DTOs\CreateOrderDTO;
use App\DTOs\OrderDTO;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function createOrder(CreateOrderDTO $orderDTO, array $items): Order;
    public function getUserOrders(int $userId): LengthAwarePaginator;
    public function getOrderDetails(int $orderId, int $userId): ?Order;
    public function updateOrderStatus(Order $order, string $status): Order;
    public function getAllOrders(): LengthAwarePaginator;
    public function getById(int $orderId): Order;
}