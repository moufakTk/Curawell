<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalHistory>
 */
class MedicalHistoryFactory extends Factory
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
            'chronic_diseases'=>fake()->sentences(rand(1,4)),
            'hereditary_diseases'=>fake()->sentences(rand(1,4)),
            'new_diseases'=>fake()->sentences(rand(1,4)),
            'allergies'=>fake()->sentences(rand(1,4)),
            'blood_group'=>fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'weight'=>fake()->randomFloat(1, 40, 150),
            'height'=>fake()->randomFloat(1, 140, 200),

        ];
    }
}
