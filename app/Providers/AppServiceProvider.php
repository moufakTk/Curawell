<?php

namespace App\Providers;

use App\Models\AnalyzeOrder;
use App\Models\AppointmentHomeCare;
use App\Models\SessionCenter;
use App\Models\SkiagraphOrder;
use App\Models\User;
use App\Models\UserReplacement;
use App\Observers\AnalyzeOrderObserver;
use App\Observers\HomeCareObserver;
use App\Observers\SessionCenterObserver;
use App\Observers\SkiagraphOrderObserver;
use App\Observers\UserObserver;
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

        AnalyzeOrder::observe(AnalyzeOrderObserver::class);
        SkiagraphOrder::observe(SkiagraphOrderObserver::class);
        AppointmentHomeCare::observe(HomeCareObserver::class);
        User::observe(UserObserver::class);
        SessionCenter::observe(SessionCenterObserver::class);
        UserReplacement::observe(UserReplacementObserver::class);
        Route::model('patient', \App\Models\Patient::class);

    }
}
