<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;


   // Route::get('/cart', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'addToCart']);

    // Route::put('/cart/{cartId}', [CartController::class, 'update']);
    // Route::delete('/cart/{cartId}', [CartController::class, 'destroy']);

