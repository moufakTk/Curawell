<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $prefixes = [
            '93', // Syriatel
            '94', // MTN
            '95', // خطوط افتراضية
            '96', // خطوط افتراضية
            '98', // خطوط افتراضية
            '99', // خطوط افتراضية
        ];

        return [
            //
            'civil_id_number'=>fake()->unique()->numerify( '###########'),
            'alternative_phone'=>fake()->unique()->numerify('+963' . fake()->randomElement($prefixes) . '#######'),

        ];
    }
}
