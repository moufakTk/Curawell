<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SmallService>
 */
class SmallServiceFactory extends Factory
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
            'section_id'=>1,
            'name_en'=>'zezo al mistro',
            'name_ar'=>'خدمة الحمودة',
            'price'=>fake()->numberBetween(200 ,1000),
            'description_en'=>fake()->text(),
            'description_ar'=>'   هاد يا سيدي خدمة الحمودة خص نص لأبو ابراهيم الطير <c',

        ];
    }
}
