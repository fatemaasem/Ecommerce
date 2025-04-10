<?php


use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // المسارات التي لا تحتاج إلى مصادقة
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // المسارات التي تحتاج إلى مصادقة
    Route::middleware('auth:jwt')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        //for test role middleware
        Route::get('test',function(){
            return "success";
        })->middleware('role:client');
        
    });
});
