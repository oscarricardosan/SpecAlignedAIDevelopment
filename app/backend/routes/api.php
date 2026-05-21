<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::get('/tokens', [ApiAuthController::class, 'tokens']);
    Route::delete('/tokens/{tokenId}', [ApiAuthController::class, 'revokeToken']);
});
