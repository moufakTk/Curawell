<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
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
            'name_en' => fake()->name(),
            'name_ar' => 'لاجل عيون اب زهير خصم خصوووم',
            'description_en'=>fake()->text(),
            'description_ar'=>'خصمنا عبارة عن شفرات حلاقة مخصصة لحلاقة شعر الرجلين',
            'discount_rate'=>fake()->randomFloat(2,1,100),


        ];
    }
}
