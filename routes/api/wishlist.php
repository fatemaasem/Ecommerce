<?php
use App\Http\Controllers\API\WishlistController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:jwt')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::put('/wishlist/{wishlistId}', [WishlistController::class, 'update']);
    Route::delete('/wishlist/{wishlistId}', [WishlistController::class, 'destroy']);
});
