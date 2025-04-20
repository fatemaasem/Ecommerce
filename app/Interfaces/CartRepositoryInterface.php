<?php

// app/Repositories/Contracts/WishlistRepositoryInterface.php
namespace App\Interfaces;;

use App\DTOs\AddToCartDTO;
use App\DTOs\CartItemDTO;
use App\DTOs\WishlistDTO;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function addToCart(AddToCartDTO $data,string $sessionId) ;
    public function initializeCart(string $sessionId);
    public function isProductInCart($product_id,$sessionId);
    // public function updateCart( $cart,CartItemDTO $data);
    // public function removeFromWishlist(int $wishlistId): bool;
    // public function getUserWishlist(int $userId): LengthAwarePaginator;
    // public function getUserWishlistWithoutPaginate(int $userId): Collection;
    // public function isProductInWishlist(int $userId, int $productId): bool;
    // public function find(int $wishlistId): ?Wishlist;
    // public function clearUserWishlist(int $userId): bool;
}