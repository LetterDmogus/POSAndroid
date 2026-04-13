<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel API is connected successfully!',
        'timestamp' => now()
    ]);
});

// API POS Routes
Route::apiResource('barangs', BarangController::class);
Route::get('barangs/scan/{sku}', [BarangController::class, 'scan']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
