<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\Language\SetLocaleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register'])->middleware(SetLocaleMiddleware::class);



Route::post('/create/user',[\App\Http\Controllers\Admin\CRUDController::class, 'createUser']);
Route::middleware('role:admin')->group(function () {
    //Route::post('/create/user',[\App\Http\Controllers\Admin\CRUDController::class, 'createUser']);

});
