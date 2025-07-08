<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

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