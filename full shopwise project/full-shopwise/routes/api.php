<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartItemController;

use App\Http\Controllers\CategoryController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);

// Produk (public access)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Route dengan token (butuh login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Cart
    Route::get('/cart', [CartItemController::class, 'index']);
    Route::post('/cart', [CartItemController::class, 'add']);
    Route::delete('/cart/{id}', [CartItemController::class, 'delete']);

    // Order
    Route::post('/checkout', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'history']);
});