<?php

namespace Database\Factories;

use App\Enums\WorkStatus\DayStatus;
use App\Enums\WorkStatus\PeriodStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkEmployee>
 */
class WorkEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $startHour = rand(8, 10);
        $durationHours = rand(4, 6); // 4 لـ 6 ساعات دوام
        $from = Carbon::createFromTime($startHour, 0, 0);
        $to = $from->copy()->addHours($durationHours);
        return [
            //

            'status'=>fake()->randomElement(PeriodStatus::cases())->value,
            'from' => $from->format('H:i:s'),
            'to' => $to->format('H:i:s'),

        ];
    }
}
