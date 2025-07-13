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
        Route::post('/auth/google/callback', 'loginWithGoogle')->name('loginWithGoogle');
//        Route::get('/auth/google/callback', 'callback');
//        Route::get('/auth/google/redirect', 'redirect');

    });

    Route::prefix('auth')->group(function () {
        Route::controller(VerificationController::class)->group(function () {
            Route::post('/send-code', 'sendCode');
            Route::post('/verify-code', 'verifyCode');

        });
        Route::post('/reset-password', [PasswordController::class,'resetPassword']);   // إعادة تعيين كلمة المرور
    });
});


Route::post('/register', [AuthController::class, 'register'])->middleware(SetLocaleMiddleware::class);

Route::post('create/user',[\App\Http\Controllers\Admin\CRUDController::class, 'createUser']);


Route::middleware(['api'])->group(function () {
                                    /* home page & landing page  */
    Route::get('/Info-center' ,[\App\Http\Controllers\CenterInfoController::class ,'getInfo'])->name('settings.Info');
    Route::get('/Info-contact_us' ,[\App\Http\Controllers\CenterInfoController::class ,'contactUs'])->name('settings.contactUs');
    Route::get("/get_record_user",[\App\Http\Controllers\CenterInfoController::class,'getRecords']);
    Route::get("/get_sections" ,[\App\Http\Controllers\CenterInfoController::class,'getSections']);
    Route::get("/get_clinics" ,[\App\Http\Controllers\CenterInfoController::class,'getClinics']);
    Route::get("/get_Top_doctors" ,[\App\Http\Controllers\CenterInfoController::class,'doctorTop'])->name('doctors.index');
    Route::get("/get_comments" ,[\App\Http\Controllers\CenterInfoController::class,'comments'])->name('patient.index');
    Route::get('/get_articles' ,[\App\Http\Controllers\CenterInfoController::class,'articles']);
    Route::get('/get_discounts' ,[\App\Http\Controllers\CenterInfoController::class,'offers']);

                                    /*  Clinics page  */
    Route::get('/get_questions' ,[\App\Http\Controllers\CenterInfoController::class,'frequentlyQuestion']);

});

