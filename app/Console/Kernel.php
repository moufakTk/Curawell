<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $deleted = VerificationCode::where('expires_at', '<=', now())->delete();
            Log::info("Deleted $deleted expired verification codes.");

        })->everyMinute();

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
