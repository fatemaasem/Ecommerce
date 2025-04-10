<?php

use App\Http\Controllers\Api\OfferController;
use Illuminate\Support\Facades\Route;
//  راوتات offer المتاحة للجميع
Route::get('/offers', [OfferController::class, 'index']);
Route::get('/offers/{offer}', [OfferController::class, 'show']);

//  راوتات offer التي تتطلب المصادقة
Route::middleware(['auth:jwt'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/offers', [OfferController::class, 'store']);
        Route::put('/offers/{offer}', [OfferController::class, 'update']);
        Route::delete('/offers/{offer}', [OfferController::class, 'destroy']);
    });
});
