<?php

namespace App\Providers;

use App\Models\UserReplacement;
use App\Observers\UserReplacementObserver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        UserReplacement::observe(UserReplacementObserver::class);
        Route::model('patient', \App\Models\Patient::class); // إضافة يدوية إذا لزم الأمر

    }
}
