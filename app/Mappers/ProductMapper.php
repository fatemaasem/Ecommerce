<?php

namespace App\Mappers;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Auth;

class ProductMapper
{
    /**
     * تحويل `Product` إلى `ProductDTO`
     */
    public static function toDTO(Product $product): ProductDTO
    {
        return new ProductDTO(
            name: $product->name,
            price: $product->price,
            description: $product->description,
            category_id: $product->category_id,
            stock_quantity: $product->stock_quantity,
            stripe_price_id:$product->stripe_price_id
        );
    }

    /**
     * تحويل مجموعة منتجات إلى مصفوفة من `ProductDTO`
     */
    public static function toDTOCollection($products): array
    {
        return [
            'current_page' => $products->currentPage(),
            'total_pages' => $products->lastPage(),
           
            'data' => $products->map(fn ($product) => self::toResponse($product))->toArray(),
        ];
    }

      /**
     * تحويل `DTO` إلى كائن JSON يحتوي على بيانات إضافية
     */
    public static function toResponse(Product $product): array
    {
        return [
            'id' => $product->id,
            'stripe_price_id'=>$product->stripe_price_id,
            'name' => $product->name,
            'price' => $product->price,
            'discounted_price' => app('App\Services\OfferService')->calculateDiscountedPrice($product),
            'description' => $product->description,
            'category_name'=>$product->category->name,
            'stock_quantity' => $product->stock_quantity,
            'images' => $product->images->pluck('full_image_url')->toArray(), // الصور
            'featured'=>$product->featured?true:false,
            'popular'=>$product->popular?true:false,
            'is_wishlisted'=>app(CartService::class)->isProductInCart($product->id),
            "user_auth_name"=>Auth::user()->name,
            'category'=>CategoryMapper::toResponse($product->category),
            'admin' => [// admin add this product
                'id' => $product->user->id,
                'name' => $product->user->name,
            ],
            'affected_offer' => app('App\Services\OfferService')->getActiveOffer($product)
        ];
    }

    public static function toModelArray(ProductDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'price' => $dto->price,
           
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'stock_quantity' => $dto->stock_quantity,
            'user_id'=>Auth::guard('jwt')->user()->id,
            'featured'=>$dto->featured,

            'popular'=>$dto->popular,
            "stripe_price_id"=>$dto->stripe_price_id
            
        ];
    }
}
