<?php

use App\Models\VerificationCode;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $deleted = VerificationCode::where('expires_at', '<=', now())->delete();
    Log::info("Deleted $deleted expired verification codes.");

})->everyMinute();
