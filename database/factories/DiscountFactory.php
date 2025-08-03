<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
        $service =Service::where('section_id' ,1)->pluck('id')->toArray();
        return [
            //

            'service_id'=> Arr::random($service),
            'name_en' => fake()->name(),
            'name_ar' => 'لاجل عيون اب زهير خصم خصوووم',
            'description_en'=>fake()->text(),
            'description_ar'=>'خصمنا عبارة عن شفرات حلاقة مخصصة لحلاقة شعر الرجلين',
            'discount_rate'=>fake()->randomFloat(2,1,100),


        ];
    }
}
