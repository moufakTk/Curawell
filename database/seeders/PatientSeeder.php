<?php

namespace Database\Seeders;

use App\Enums\Users\UserType;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::where('user_type' ,UserType::Patient)->get()->each(function ($user) {
            $patient =Patient::factory()->create(['user_id' => $user->id]);
            MedicalHistory::factory()->create(['patient_id' => $patient]);
        });


    }
}
