<?php

use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment endpoints
Route::prefix('payments')->group(function () {
    Route::post('/deposit', [PaymentController::class, 'createDeposit']);
    Route::post('/complete', [PaymentController::class, 'completeDeposit']);
    Route::post('/spend', [PaymentController::class, 'processAdSpend']);
    Route::get('/balance', [PaymentController::class, 'getBalance']);
});

// Driver App API endpoints
Route::prefix('driver')->group(function () {
    // Authentication (no auth required)
    Route::post('/login', [DriverController::class, 'login']);

    // Protected routes (require driver authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Account Management
        Route::post('/logout', [DriverController::class, 'logout']);
        Route::get('/profile', [DriverController::class, 'profile']);

        // Ad Serving & Display
        Route::post('/ads/request', [DriverController::class, 'requestAds']); // Get ads for current location
        Route::post('/ads/{ad}/impression', [DriverController::class, 'recordImpression']); // Log ad view

        // Location Management & Tracking
        Route::post('/location/update', [DriverController::class, 'updateLocation']); // Update current location
        Route::get('/location/history', [DriverController::class, 'getLocationHistory']); // Get location history
        Route::get('/location/analytics', [DriverController::class, 'getMovementAnalytics']); // Get movement analytics

        // Earnings & Payouts
        Route::get('/earnings/summary', [DriverController::class, 'getEarningsSummary']);
    });
});

// QR Code Tracking (public routes)
Route::prefix('qr')->group(function () {
    Route::get('/{qr_code}', [DriverController::class, 'redirectQR']); // QR redirect with tracking
});
