<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Competence;
use App\Models\Doctor;
use App\Models\Section;
use App\Models\User;
use App\Models\WorkLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //



        $competence=Competence::pluck('id')->toArray();
        $id_Array_competence=0;

        $user_id_of_doctor=[];
        $doctors=Doctor::where('doctor_type',DoctorType::Clinic);
        $doctors->each(function ($doctor) use(&$user_id_of_doctor) {
            $user_id_of_doctor[]=$doctor->user_id;
        });
        collect($user_id_of_doctor)->chunk(4)->each(function ($chunk) use($competence ,&$id_Array_competence) {
            foreach ($chunk as $user_id) {
                WorkLocation::create([
                    'user_id'=>$user_id,
                    'locationable_type'=>Competence::class,
                    'locationable_id'=>$competence[$id_Array_competence],
                ]);
            }
            $id_Array_competence++;
        });

        $locationable_id=Section::where('section_type',SectionType::HomeCare)->value('id');
        $nurse=User::where('user_type',UserType::Nurse)->limit(2)->get()->each(function ($user) use($locationable_id) {
            WorkLocation::create([
                'user_id'=>$user->id,
                'locationable_type'=>Section::class,
                'locationable_id'=>$locationable_id,
            ]);
        });


    }
}
