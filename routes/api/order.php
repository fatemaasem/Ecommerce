<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth'])->group(function () {
    // User routes
    Route::middleware(['role:client'])->group(function () {
    Route::post('/orders/create-from-wishlist', [OrderController::class, 'createOrderFromWishlist']);
    Route::get('/orders', [OrderController::class, 'getUserOrders']);
    });
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);
    
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::put('/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);
        Route::get('/admin/orders', [OrderController::class, 'getAllOrders']);
    });
});