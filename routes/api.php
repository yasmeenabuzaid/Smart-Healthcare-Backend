<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;
use App\Http\Controllers\Api\Auth\ApiLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('app')->group(function () {

    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('/register', [ApiRegisterController::class, 'register']);
        
        Route::post('/login/request', [ApiLoginController::class, 'requestLogin']);
        Route::post('/login/verify', [ApiLoginController::class, 'verifyLogin']);
        
        Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/auth/logout', [ApiLoginController::class, 'logout']);
        
    });

});