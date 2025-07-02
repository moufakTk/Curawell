<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Middleware\Language\SetLocaleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware(SetLocaleMiddleware::class)->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::post('/auth/google/callback', 'loginWithGoogle');

    });

    Route::prefix('auth')->group(function () {
        Route::controller(VerificationController::class)->group(function () {
            Route::post('/send-code', 'sendCode');
            Route::post('/verify-code', 'verifyCode');

        });
        Route::post('/reset-password', [PasswordController::class,'resetPassword']);   // إعادة تعيين كلمة المرور
    });
});



