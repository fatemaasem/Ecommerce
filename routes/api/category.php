<?php

use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

//  راوتات الفئات المتاحة للجميع
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/categories/{category}', [ApiCategoryController::class, 'show']);

//  راوتات الفئات التي تتطلب المصادقة
Route::middleware(['auth:jwt'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/categories', [ApiCategoryController::class, 'store']);
        Route::put('/categories/{category}', [ApiCategoryController::class, 'update']);
        Route::delete('/categories/{category}', [ApiCategoryController::class, 'destroy']);
    });
});
