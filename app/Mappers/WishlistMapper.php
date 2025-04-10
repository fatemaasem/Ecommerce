<?php
namespace App\Mappers;

use App\DTOs\WishlistDTO;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Repositories\ProductImageRepository;

class WishlistMapper{
    public  function toDTO(Wishlist $wishlist): WishlistDTO
    {
        return new WishlistDTO(
            $wishlist->id,
            $wishlist->user_id,
            $wishlist->product_id,
            $wishlist->quantity,
            $wishlist->created_at->toDateTimeString(),
            $wishlist->product
        );
    }
    public static function toResponse(Wishlist $wishlist): array
    {
        return [
            'id'=> $wishlist->id,
            'user_id' =>  $wishlist->user_id,
            'product_id'=>$wishlist->product_id,
            'quantity'=> $wishlist->quantity,
            'created_at'=> $wishlist->created_at->toDateTimeString(),
            'productImages'=>ProductImageMapper::toCollectionResponse(app(ProductImageRepository::class)->getByProductId($wishlist->product_id)) 
        ];
    }


    public static function toDTOCollection($wishlists): array
    {
        return [
            'current_page' => $wishlists->currentPage(),
            'total_pages' => $wishlists->lastPage(),
           
            'data' => $wishlists->map(fn ($wishlist) => self::toResponse($wishlist))->toArray(),
        ];
    }
}