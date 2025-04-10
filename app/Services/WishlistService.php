<?php
// app/Services/WishlistService.php

namespace App\Services;

use App\DTOs\WishlistDTO;
use App\Exceptions\CustomExceptions;
use App\Http\Requests\UpdateWishlistRequest;
use App\Interfaces\WishlistRepositoryInterface;
use App\Mappers\WishlistMapper;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function __construct(
        private WishlistRepositoryInterface $repository
    ) {}

    public function addToWishlist( WishlistDTO $wishlistDTO): array
    {
        
        return WishlistMapper::toResponse($this->repository->addToWishlist($wishlistDTO)) ;
    }

    public function updateWishlist(int $wishlistId, UpdateWishlistRequest $request): array
    {
        $wishlist = $this->repository->find($wishlistId);
        if (!$wishlist) {
            throw CustomExceptions::notFoundError("Wishlist with ID {$wishlistId} not found.");
        }
        $wishlistDTO=WishlistDTO::fromRequest(Auth::user()->id,$wishlist->product_id,$request->quantity);
       

        return WishlistMapper::toResponse($this->repository->updateWishlist($wishlist, $wishlistDTO));
    }

    public function removeFromWishlist(int $wishlistId): bool
    {
        return $this->repository->removeFromWishlist($wishlistId);
    }

    public function getUserWishlist(int $userId): array
    {
        return WishlistMapper::toDTOCollection($this->repository->getUserWishlist($userId));
    }

    public function isProductInWishlist(int $userId, int $productId): bool
    {
        return $this->repository->isProductInWishlist($userId, $productId);
    }
}