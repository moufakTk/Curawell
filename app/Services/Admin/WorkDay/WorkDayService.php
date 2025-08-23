<?php

namespace App\Services\Admin\WorkDay;

use App\Models\WorkDay;
use Carbon\Carbon;

class WorkDayService
{
    public function ensureRange(Carbon $from, Carbon $to, int $defaultStatus = 1): array
    {
        $created = 0; $skipped = 0;

        for ($d = $from->copy()->startOfDay(); $d->lte($to->copy()->startOfDay()); $d->addDay()) {
            $dayEn = $d->locale('en')->isoFormat('dddd'); // Sunday..Saturday
            $dayAr = config('schedule.weekday_ar')[$dayEn] ?? $dayEn;

            $wd = WorkDay::firstOrCreate(
                ['history' => $d->toDateString()],
                ['day_en' => $dayEn, 'day_ar' => $dayAr, 'status' => 1]
            );
            $wd->status ??= $defaultStatus;

            $wd->wasRecentlyCreated ? $created++ : $skipped++;
        }

        return compact('created','skipped');
    }

    public function countOpenFutureDays(Carbon $from = null): int
    {
        $from = $from?->toDateString() ?? now()->toDateString();
        return WorkDay::whereDate('history', '>=', $from)->where('status', 1)->count();
    }

    public function listRange(?string $from, ?string $to, ?int $status = null)
    {
        $q = WorkDay::query()->orderBy('history','asc');
        if ($from) $q->whereDate('history','>=',$from);
        if ($to)   $q->whereDate('history','<=',$to);
        if (!is_null($status)) $q->where('status',$status);
        return $q->paginate(60);
    }

    public function toggle(WorkDay $day): WorkDay
    {
        $day->status = (int)!$day->status;
        $day->save();
        return $day;
    }

    public function setStatus(WorkDay $day, int $status): WorkDay
    {
        $day->status = $status ? 1 : 0;
        $day->save();
        return $day;
    }

    public function openNextDays(int $days = 45, int $defaultStatus = 1): array
    {
        // آخر يوم موجود
        $last = WorkDay::query()
            ->orderByDesc('history')
            ->value('history'); // string|NULL

        // ابدأ من اليوم التالي لآخر تاريخ، أو من اليوم الحالي لو ما في سجلات/كان أقدم من اليوم
        $start = $last
            ? Carbon::parse($last)->addDay()->startOfDay()
            : now()->startOfDay();

        // لو آخر تاريخ بالماضي قبل اليوم، نبدأ من اليوم
        if ($start->lt(now()->startOfDay())) {
            $start = now()->startOfDay();
        }

        $end = $start->copy()->addDays($days - 1);

        $res = $this->ensureRange($start, $end, $defaultStatus);

        return [
            'from'    => $start->toDateString(),
            'to'      => $end->toDateString(),
            'created' => $res['created'],
            'skipped' => $res['skipped'],
        ];
    }

    public function autoTopUpIfNeeded(int $days = 45, int $threshold = 10): ?array
    {
        $open = $this->countOpenFutureDays();
        if ($open >= $threshold) return null; // ما في داعي

        $from = now()->startOfDay();
        $to   = now()->copy()->addDays($days - 1)->startOfDay();

        $res = $this->ensureRange($from, $to, 1);
        return [
            'from'    => $from->toDateString(),
            'to'      => $to->toDateString(),
            'created' => $res['created'],
            'skipped' => $res['skipped'],
        ];
    }
}
