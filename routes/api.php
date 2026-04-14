<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;

// Public Routes (Tanpa Login)
Route::post('login', [AuthController::class, 'login']);

Route::get('/hello', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel API is connected successfully!',
        'timestamp' => now()
    ]);
});

// Protected Routes (Harus Login / Pakai Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);

    // API POS Routes
    Route::apiResource('barangs', BarangController::class);
    Route::get('barangs/scan/{sku}', [BarangController::class, 'scan']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
    Route::get('invoice/{nomor_invoice}', [OrderController::class, 'invoice']);
});
