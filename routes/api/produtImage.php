<?php

use App\Http\Controllers\Api\ProductImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:jwt'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/product-images', [ProductImageController::class,'store']);
        Route::delete('/product-images/{image}', [ProductImageController::class,'destroy']);
    });
});

Route::get('/product-images/{product_id}', [ProductImageController::class, 'getByProductId']);
