<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('Products', ProductController::class);
Route::apiResource('CartItem', CartItemController::class);
