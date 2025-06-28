<?php

namespace Database\Factories;

use App\Enums\Users\DoctorType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doctor;
use Faker\Factory as FakerFactory;

// FakerFactory::create('en_US');// جُمل إنجليزية
// FakerFactory::create('ar_SA');// جُمل إنجليزية
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
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
        $date =fake()->date('Y-m-d');

        $a =rand(3,6);
       // dd($fakerAr->text());

        return [
            //
            'respective_en'=>$fakerEn->text(),
            'respective_ar'=>$fakerAr->text(),
            "experience_years"=>fake()->numberBetween(1,50),
            'services_en'=>$fakerEn->sentences($a),
            'services_ar'=>$fakerAr->sentences($a),
            'bloodGroup'=>fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'start_in'=>$date,
            'hold_end'=>$date,
            'evaluation'=>rand(1, 5),
            'doctor_type'=>fake()->randomElement(DoctorType::cases())->value,

        ];
    }

    public function doctorClinic(): static
    {
        return $this->state(fn () => [
            'user_type' => DoctorType::Clinic->value,
        ]);
    }


    public function doctorLab(): static
    {
        return $this->state(fn () => [
            'user_type' => DoctorType::Laboratory->value,
        ]);
    }

    public function doctorRad(): static
    {
        return $this->state(fn () => [
            'user_type' => DoctorType::Radiographer->value,
        ]);
    }

    public function doctorRelief(): static
    {
        return $this->state(fn () => [
            'user_type' => DoctorType::Relief->value,
        ]);
    }



}
