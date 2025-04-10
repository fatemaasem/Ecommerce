<?php
namespace App\Repositories;
use App\DTOs\WishlistDTO;
use App\Exceptions\CustomExceptions;
use App\Models\Wishlist;
use App\Interfaces\WishlistRepositoryInterface;
use App\Mappers\WishlistMapper;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function addToWishlist(WishlistDTO $wishlistDTO): Wishlist
    {
        $wishlist = Wishlist::updateOrCreate(
             // Search conditions
        [
            'user_id' => $wishlistDTO->userId,
            'product_id' => $wishlistDTO->productId
        ],
        // Values to update/create
        [
            'quantity' => $wishlistDTO->quantity
        ]
        );
        return $wishlist;
        
    }

    public function updateWishlist(Wishlist $wishlist,WishlistDTO $wishlistDTO):Wishlist
    {
       
        $wishlist->update( ['quantity' => $wishlistDTO->quantity]);
        
        return $wishlist;
    }

    public function removeFromWishlist(int $wishlistId): bool
    {
        $wishlist = Wishlist::find($wishlistId);
        
        if (!$wishlist) {
            throw CustomExceptions::notFoundError("Wishlist with ID {$wishlistId} not found.");
        }

        return $wishlist->delete();
    }

    public function getUserWishlist(int $userId): LengthAwarePaginator
    {
        $wishlists=Wishlist::where('user_id', $userId)->paginate(50);
      
        return  $wishlists;
          
    }
    public function getUserWishlistWithoutPaginate(int $userId): Collection // to create order
    {
        $wishlists=Wishlist::where('user_id', $userId)->get();
      
        return  $wishlists;
          
    }

    public function isProductInWishlist(int $userId, int $productId): bool
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function find(int $wishlistId): ?Wishlist
    {
        return Wishlist::find($wishlistId);
    }
    
    public function clearUserWishlist(int $userId): bool
    {
        try {
            DB::beginTransaction();
            
            $deletedCount = Wishlist::where('user_id', $userId)->delete();
            
            DB::commit();
            
          
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
           
            return false;
        }
    }
   
}
