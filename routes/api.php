<?php

use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('product/create', [ProductController::class, 'create']);
Route::get('products', [ProductController::class, 'list']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::post('/product', [ProductController::class, 'product']);
Route::post('/product/sell', [ProductController::class, 'sell']);
Route::post('/product/update', [ProductController::class, 'update']);
