<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDayTime>
 */
class UserDayTimeFactory extends Factory
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

            'timeStart'=> sprintf('%02d:00', rand(8, 10)),
            'timeEnd'=> sprintf('%02d:00', rand(4, 7)),
        ];
    }
}
