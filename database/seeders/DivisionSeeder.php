<?php

namespace Database\Seeders;

use App\Enums\Users\DoctorType;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\SmallService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use PhpParser\Comment\Doc;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $small_services =SmallService::pluck('id')->toArray();


        $doctors =Doctor::where('doctor_type',DoctorType::Clinic)->pluck('id')->each(function ($doctorId) use ($small_services) {

            for($i=0 ; $i<10 ; $i++){
                Division::factory()->create([
                    'doctor_id' => $doctorId,
                    'small_service_id' => Arr::random($small_services),
                ]);
            }
        });


//        collect(range(1, 100))->each(function () use ($doctors, $small_services) {
//
//        });







    }
}
