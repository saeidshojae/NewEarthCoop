<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NajmBaharController;

Route::prefix('najm-bahar')->group(function () {
    Route::post('accounts', [NajmBaharController::class, 'createAccount']);
    Route::get('accounts/{accountNumber}/balance', [NajmBaharController::class, 'getBalance']);

    // transaction endpoints (authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('transactions/transfer', [\App\Http\Controllers\Api\NajmBaharTransactionController::class, 'transfer']);
        Route::post('transactions/schedule', [\App\Http\Controllers\Api\NajmBaharTransactionController::class, 'schedule']);
        Route::get('transactions', [\App\Http\Controllers\Api\NajmBaharTransactionController::class, 'index']);
        Route::get('ledger/{accountNumber}', [\App\Http\Controllers\Api\NajmBaharTransactionController::class, 'ledger']);
    });
});

// Note: include this file from RouteServiceProvider or require it from routes/api.php when ready.
