<?php
// app/Mappers/OrderMapper.php
namespace App\Mappers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\ProductImageRepository;

class OrderMapper
{
    public static function toResponse(Order $order): array
    {
        return [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'total_price' => (float) $order->total_price,
            'status' => $order->status,
            'created_at' => $order->created_at->toDateTimeString(),
            'items' => array_map(function (OrderItem $item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price,
                    'productImages'=>ProductImageMapper::toCollectionResponse(app(ProductImageRepository::class)->getByProductId($item->product_id))
                ];
            }, $order->items->all())
        ];
    }

    public static function toDTOCollection($orders): array
    {
        return [
            'current_page' => $orders->currentPage(),
            'total_pages' => $orders->lastPage(),
           
            'order' => $orders->map(fn ($order) => self::toResponse($order))->toArray(),
        ];
    }
}