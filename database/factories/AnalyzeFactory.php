<?php

namespace Database\Factories;

use App\Models\Analyze;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyzeFactory extends Factory
{
    protected $model = Analyze::class;

    public function definition(): array
    {
        return [
            'name_en'        => $this->faker->word . ' Test',
            'name_ar'        => $this->faker->word . ' تحليل',
            'type'           => $this->faker->randomElement(['Blood', 'Urine', 'X-Ray']),
            'price'          => $this->faker->numberBetween(1000, 10000),
            'sample_type'    => $this->faker->randomElement(['Blood', 'Urine']),
            'sample_validate'=> $this->faker->boolean,
            'is_active'      => true,
        ];
    }
}
