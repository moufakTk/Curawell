<?php

namespace Database\Seeders;

use App\Enums\Users\DoctorType;
use App\Models\Doctor;
use App\Models\Evaluction;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $doctor= Doctor::where('doctor_type',DoctorType::Clinic)->pluck('id');
        Patient::pluck('id')->each(function ($patient) use ($doctor) {
            collect([1, 2])->each(function () use ($doctor , $patient) {
                Evaluction::factory()->create([
                    'doctor_id'  => $doctor->random(),
                    'patient_id' => $patient,
                ]);
            });
        });



    }
}
