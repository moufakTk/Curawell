<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FrequentlyQuestion>
 */
class FrequentlyQuestionFactory extends Factory
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

            "question_en"=>fake()->text(),
            "question_ar"=>'أبو الفدى قياساتي شو القياساتي المفضلة؟!',
            'answer_en'=>fake()->text(),
            'answer_ar'=>'عييييييب كل القياساتي لي وانا للقياساتي.....انقر للمزيد',
            'status'=>fake()->boolean(),
        ];
    }
}
