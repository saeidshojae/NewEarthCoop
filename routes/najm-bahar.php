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
        
        // sub-account endpoints
        Route::get('sub-accounts', [\App\Http\Controllers\Api\NajmBaharSubAccountController::class, 'index']);
        Route::post('sub-accounts', [\App\Http\Controllers\Api\NajmBaharSubAccountController::class, 'store']);
        Route::get('sub-accounts/{subAccount}', [\App\Http\Controllers\Api\NajmBaharSubAccountController::class, 'show']);
        Route::post('sub-accounts/{subAccount}/transfer-to', [\App\Http\Controllers\Api\NajmBaharSubAccountController::class, 'transferTo']);
        Route::post('sub-accounts/{subAccount}/transfer-from', [\App\Http\Controllers\Api\NajmBaharSubAccountController::class, 'transferFrom']);
    });
});

// Note: include this file from RouteServiceProvider or require it from routes/api.php when ready.
