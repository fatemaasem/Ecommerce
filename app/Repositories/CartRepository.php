<?php
namespace App\Repositories;

use App\DTOs\AddToCartDTO;
use App\DTOs\CartItemDTO;
use App\DTOs\WishlistDTO;
use App\Exceptions\CustomExceptions;
use App\Models\Wishlist;
use App\Interfaces\CartRepositoryInterface;
use App\Mappers\WishlistMapper;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartRepository implements cartRepositoryInterface
{
   
    public function initializeCart(string $sessionId)
    {
        
       return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
    public function isProductInCart($product_id,$sessionId){
        $cart=$this->initializeCart($sessionId);
        $existingProduct = $cart->products()->find($product_id);
        
        if($existingProduct) return true;
        return false;
    }

    public function addToCart(AddToCartDTO $cartDTO,string $sessionId)
    {
        $cart=$this->initializeCart($sessionId);
        $existingProduct = $cart->products()->find($cartDTO->productId);

        if ($existingProduct) {
            $cart->products()->updateExistingPivot($cartDTO->productId, [
                'quantity' => $existingProduct->pivot->quantity + $cartDTO->quantity
            ]);
        } else {
            $cart->products()->attach($cartDTO->productId, ['quantity' => $cartDTO->quantity]);
        }

        return $cart->fresh();
        
    }

    // public function updateWishlist(Wishlist $wishlist,WishlistDTO $wishlistDTO):Wishlist
    // {
       
    //     $wishlist->update( ['quantity' => $wishlistDTO->quantity]);
        
    //     return $wishlist;
    // }

    // public function removeFromWishlist(int $wishlistId): bool
    // {
    //     $wishlist = Wishlist::find($wishlistId);
        
    //     if (!$wishlist) {
    //         throw CustomExceptions::notFoundError("Wishlist with ID {$wishlistId} not found.");
    //     }

    //     return $wishlist->delete();
    // }

    // public function getUserWishlist(int $userId): LengthAwarePaginator
    // {
    //     $wishlists=Wishlist::where('user_id', $userId)->paginate(50);
      
    //     return  $wishlists;
          
    // }
    // public function getUserWishlistWithoutPaginate(int $userId): Collection // to create order
    // {
    //     $wishlists=Wishlist::where('user_id', $userId)->get();
      
    //     return  $wishlists;
          
    // }

    // public function isProductInWishlist(int $userId, int $productId): bool
    // {
    //     return Wishlist::where('user_id', $userId)
    //         ->where('product_id', $productId)
    //         ->exists();
    // }

    // public function find(int $wishlistId): ?Wishlist
    // {
    //     return Wishlist::find($wishlistId);
    // }
    
    // public function clearUserWishlist(int $userId): bool
    // {
    //     try {
    //         DB::beginTransaction();
            
    //         $deletedCount = Wishlist::where('user_id', $userId)->delete();
            
    //         DB::commit();
            
          
            
    //         return true;
            
    //     } catch (\Exception $e) {
    //         DB::rollBack();
            
           
    //         return false;
    //     }
    // }
   
}
