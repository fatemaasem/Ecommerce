<?php

// app/Repositories/Contracts/WishlistRepositoryInterface.php
namespace App\Interfaces;;

use App\DTOs\WishlistDTO;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WishlistRepositoryInterface
{
    public function addToWishlist(WishlistDTO $data): Wishlist;
    public function updateWishlist(Wishlist $wishlist,WishlistDTO $data): Wishlist;
    public function removeFromWishlist(int $wishlistId): bool;
    public function getUserWishlist(int $userId): LengthAwarePaginator;
    public function getUserWishlistWithoutPaginate(int $userId): Collection;
    public function isProductInWishlist(int $userId, int $productId): bool;
    public function find(int $wishlistId): ?Wishlist;
    public function clearUserWishlist(int $userId): bool;
}