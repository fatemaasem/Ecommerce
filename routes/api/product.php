<?php

use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Support\Facades\Route;



// هذه الراوتات تتطلب المصادقة (auth:jwt)
Route::middleware(['auth:jwt'])->group(function () {
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::get('/products/{id}', [ApiProductController::class, 'show']);
    Route::get('/products/category/{category_id}', [ApiProductController::class, 'getByCategory']);

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/products', [ApiProductController::class, 'store']);
        Route::put('/products/{product}', [ApiProductController::class, 'update']);
        Route::delete('/products/{product}', [ApiProductController::class, 'destroy']);
    });
});