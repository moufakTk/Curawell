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

        $doctors =Doctor::where('doctor_type',DoctorType::Clinic)->pluck('id')->toArray();
        $small_services =SmallService::pluck('id')->toArray();

        collect(range(1, 10))->each(function () use ($doctors, $small_services) {
            Division::factory()->create([
                'doctor_id' => Arr::random($doctors),
                'small_service_id' => Arr::random($small_services),
            ]);
        });




    }
}
