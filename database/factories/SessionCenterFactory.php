<?php

namespace Database\Factories;

use App\Enums\Sessions\SessionCenterType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SessionCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'session_name'=>fake()->name(),
            'diagnosis'=>['diagnosis'=>fake()->sentence() ,'report' =>fake()->text()],
            'symptoms'=>fake()->text(),
            'medicines'=>fake()->text(),
            'session_type'=>SessionCenterType::Clinic


        ];
    }
}
