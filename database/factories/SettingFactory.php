<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
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
            '95',
            '96',
            '98',
            '99',
        ];

        return [
            //
            'inquiry_number'=>fake()->unique()->numerify('+963' . fake()->randomElement($prefixes) . '#######'),
            'complaint_number'=>fake()->unique()->numerify('+963' . fake()->randomElement($prefixes) . '#######'),
            'phone'=>fake()->unique()->numerify('+963' . fake()->randomElement($prefixes) . '#######'),
            'email'=>'moufakLap63@gmail.com',
            'site_name'=>'Curawell',
            'preface_en'=>fake()->text(),
            'preface_ar'=>'أهلا وسهلا ومرحبا ,شرفتونا وانستونا ',
            'wise_en'=>fake()->text(),
            'wise_ar'=>'خذ الحكمة من افواه المجانين',
            'address_en'=>fake()->address(),

        ];
    }
}
