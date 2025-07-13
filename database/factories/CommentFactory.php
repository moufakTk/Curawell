<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'comment_ar'=>'أحلا أبو محمد كروكودايلو 100 وردة -_- ',
            'comment_en'=>$fakerEn->text(),
            'status'=>fake()->boolean(),

        ];
    }

    public function standalone(): static
    {
        return $this->state(fn () => [
            'commentable_id' => null,
            'commentable_type' => null,
        ]);
    }

}
