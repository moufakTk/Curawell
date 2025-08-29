<?php

namespace Database\Seeders;

use App\Models\PeriodHomeCare;
use App\Models\WorkDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WorkDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonths(2);

        $daysArabic = [
            'Monday'    => 'الاثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
            'Sunday'    => 'الأحد',
        ];

        $time_period=['09:00:00','15:00:00','18:00:00'];

        while ($startDate->lte($endDate)) {
            $dayName = $startDate->format('l'); // Monday, etc.

            $work_day=WorkDay::updateOrCreate(
                ['history' => $startDate->toDateString()],
                [
                    'day_en'     => $dayName,
                    'day_ar'  => $daysArabic[$dayName],
                    'status'      => !in_array($startDate->dayOfWeek, [Carbon::FRIDAY]) ? 1 : 0,
                ]
            );

            if($work_day->status==1){
                foreach ($time_period as $time) {
                    PeriodHomeCare::create([
                        'work_day_id' => $work_day->id,
                        'date'=>$time,
                    ]);
                }

            }

            $startDate->addDay();
        }

    }
}
