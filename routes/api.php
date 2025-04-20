<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




// routes of auth
require __DIR__.'/api/auth.php';

require __DIR__.'/api/product.php';

require __DIR__.'/api/category.php';

require __DIR__.'/api/produtImage.php';

require __DIR__.'/api/offer.php';

require __DIR__.'/api/cart.php';
require __DIR__.'/api/order.php';