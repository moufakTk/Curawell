<?php

namespace Database\Seeders;

use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Doctor;
use App\Models\Doctor_examin;
use App\Models\User;
use Database\Factories\DoctorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       $users= User::where('user_type',UserType::Doctor)->get();
        $clinic =$users->take(50);
        $lab=$clinic->take(5);
        $radio=$clinic->take(2);
        $re=$clinic->take(10);

        $clinic->each(function ($clinic) {
           $doctor=Doctor::factory()->create([
               'user_id' => $clinic->id,
               'doctor_type' => DoctorType::Clinic,
           ]);
           Doctor_examin::factory()->create(['doctor_id' => $doctor->id]);
        });

        $lab->each(function ($lab) {
            Doctor::factory()->create([
                'user_id' => $lab->id,
                'doctor_type'=>DoctorType::Laboratory
            ]);
        });

        $re->each(function ($re) {
            $doctor=Doctor::factory()->create([
                'user_id' => $re->id,
                'doctor_type'=>DoctorType::Relief
            ]);
            Doctor_examin::factory()->create([
                'doctor_id' => $doctor->id,
                'is_discounted'=>false
            ]);
        });

        $radio->each(function ($radio) {
            Doctor::factory()->create([
                'user_id' => $radio->id,
                'doctor_type'=>DoctorType::Radiographer
            ]);
        });

    }
}
