<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $fakerAr = FakerFactory::create('ar_SA');
        $fakerEn= FakerFactory::create('en_SA');
        return [
            //
            'title_en'=>fake()->title(),
            'title_ar'=>fake()->title(),
            'brief_description_en'=>fake()->text(),
            'brief_description_ar'=>fake()->text(),
            'path_link'=>fake()->url(),

        ];
    }
}
