<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Division>
 */
class DivisionFactory extends Factory
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
            'is_discounted'=>$dis=fake()->boolean(),
            'discount_rate'=>$dis ===true
                ?fake()->randomFloat(1, 1, 100)
                :0 ,


        ];
    }
}
