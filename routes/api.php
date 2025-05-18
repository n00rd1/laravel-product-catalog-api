<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Models\Product;

Route::apiResource('products', ProductController::class);
Route::get('/test-products', function() {
    return Product::all();
});
