<?php

namespace Database\Seeders;

use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Discount;
use App\Models\Doctor;
use App\Models\Doctor_examin;
use App\Models\Service;
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
        $clinic =$users->splice(0, 64);
        $lab=$users->splice(0, 5);
        $radio=$users->splice(0, 2);
        $re=$users->splice(0, 10);



        $clinic->each(function ($clinic) {
           $doctor=Doctor::factory()->create([
               'user_id' => $clinic->id,
               'doctor_type' => DoctorType::Clinic,
           ]);
           $clinic->assignRole(DoctorType::Clinic->defaultRole());


            //$service=Service::first();
           $examin=Doctor_examin::factory()->create(['doctor_id' => $doctor->id]);
//           if($examin->is_discounted){
//               Discount::factory()->create(['service_id'=>1,'discountable_type' => Doctor_examin::class,'discountable_id' => $examin->id ,'discount_rate'=>$examin->discount_rate]);
//           }

        });



        $lab->each(function ($lab) {
            Doctor::factory()->create([
                'user_id' => $lab->id,
                'doctor_type'=>DoctorType::Laboratory
            ]);
            $lab->assignRole(DoctorType::Laboratory->defaultRole());
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
            $re->assignRole(UserType::Doctor->defaultRole());
        });



        $radio->each(function ($radio) {
            Doctor::factory()->create([
                'user_id' => $radio->id,
                'doctor_type'=>DoctorType::Radiographer
            ]);
            $radio->assignRole(UserType::Doctor->defaultRole());
        });

    }
}
