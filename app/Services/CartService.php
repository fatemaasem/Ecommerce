<?php

namespace App\Services;

use App\DTOs\AddToCartDTO;
use App\DTOs\CartItemDTO;
use App\Exceptions\CustomExceptions;
use App\Interfaces\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function __construct(
        private CartRepositoryInterface $repository
    ) {}


    public function getCartId(){
        $id = request()->cookie('session_id');

        if (!$id) {
            $id = Str::uuid();
            Cookie::queue('cart_id', $id, 60 * 24 * 30);
        }

       return $id;
    }
    public function isProductInCart($product_id){
        $this->repository->isProductInCart($product_id,$this->getCartId());
    }
    public function addProduct(AddToCartDTO $cartDto)
    {
        return $this->repository->addToCart($cartDto,$this->getCartId());
    
    }

    // public function updateProduct(Product $product, $quantity)
    // {
    //     $this->cart->products()->updateExistingPivot($product->id, [
    //         'quantity' => $quantity
    //     ]);

    //     return $this->cart->fresh();
    // }

    // public function removeProduct(Product $product)
    // {
    //     $this->cart->products()->detach($product->id);
    //     return $this->cart->fresh();
    // }

    // public function getCart()
    // {
    //     return $this->cart->load('products');
    // }

    // public function getTotal()
    // {
    //     return $this->cart->products->sum(function ($product) {
    //         return $product->price * $product->pivot->quantity;
    //     });
    // }

    // public function clearCart()
    // {
    //     $this->cart->products()->detach();
    //     return $this->cart->fresh();
    // }

    // public function getItemCount()
    // {
    //     return $this->cart->products->sum('pivot.quantity');
    // }
}