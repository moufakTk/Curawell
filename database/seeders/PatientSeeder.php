<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Comment;
use App\Models\Doctor;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Section;
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
        $doctors = Doctor::where('doctor_type', DoctorType::Clinic)->pluck('id');

        User::where('user_type', UserType::Patient)->get()->each(function ($user) use ($doctors) {
            $patient = Patient::factory()->create(['user_id' => $user->id]);

            MedicalHistory::factory()->create(['patient_id' => $patient]);
            $user->assignRole(UserType::Patient->defaultRole());

            Comment::factory()->standalone()->create(['patient_id' => $patient->id]);
            Comment::factory()->create([
                'patient_id' => $patient->id,
                'comment_ar'=>'أبو ابراهيم الطير ريتك تملط العير ءءء',
                'commentable_type'=>Section::class,
                'commentable_id'=>Section::where('section_type',SectionType::HomeCare)->value('id'),
                ]);
            collect([1, 2])->each(function () use ($doctors, $patient) {
                Comment::factory()->create([
                    'patient_id' => $patient->id,
                    'commentable_type' => Doctor::class,
                    'commentable_id' => $doctors->random(),
                ]);
            });



        });
    }

}
