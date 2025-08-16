<?php

namespace Database\Factories;

use App\Enums\ProcessTakeSample;
use App\Models\Sample;
use App\Models\Patient;
use App\Enums\SampleType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SampleFactory extends Factory
{
    protected $model = Sample::class;

    public function definition(): array
    {
        return [
            'patient_id' => 1, // بيربط العينة بمريض جديد بشكل افتراضي
            'process_take' => $this->faker->randomElement(ProcessTakeSample::cases())->value,
            'time_take' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'time_don' => $this->faker->dateTimeBetween('now', '+1 week'),
            'sample_type' => $this->faker->randomElement(SampleType::cases())->value,
            'status' => $this->faker->boolean,
        ];
    }
}
