<?php

namespace App\Console\Commands;

use App\Services\Admin\WorkDay\WorkDayService;
use Illuminate\Console\Command;

class WorkDayAutoTopUp extends Command
{
    protected $signature = 'workday:auto {--days=45} {--threshold=10}';
    protected $description = 'أكمل WorkDay تلقائيًا إذا الأيام المفتوحة أقل من العتبة';

    public function handle(WorkDayService $svc): int
    {
        $days = (int)$this->option('days');
        $th   = (int)$this->option('threshold');

        $res = $svc->autoTopUpIfNeeded($days, $th);

        if ($res === null) {
            $this->info('No action needed. Future open days >= threshold.');
        } else {
            $this->info("Auto top-up: Created {$res['created']}, Skipped {$res['skipped']} ({$res['from']} → {$res['to']})");
        }
        return self::SUCCESS;
    }
}
