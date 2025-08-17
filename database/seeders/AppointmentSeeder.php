<?php

namespace Database\Seeders;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Appointment;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\Patient;

use App\Models\Point;
use App\Models\SessionCenter;
use App\Models\Treatment;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $patient = Patient::first();
        $user =User::where('user_type',UserType::Doctor)->whereHas('doctor' ,function($q){
            $q->where('doctor_type',DoctorType::Clinic);
        })->with('doctor','work_employees.doctor_sessions')
         ->get();

        $point = Point::where('name_en','Book an appointment online')->first();
        $user->each(function ($user) use ($patient ,$point){

            $sessions = $user->work_employees->flatMap(fn($emp) => $emp->doctor_sessions);
            $appointment =Appointment::factory()->create([
                'patient_id' => $patient->id,
                'doctor_id' =>  $user->doctor->id,
                'doctor_session_id' =>$sessions->random()->id,
            ]);



            $userPoint=UserPoint::create([
                'patient_id'=>$patient->id,
                'point_id'=>$point->id ,
                'pointable_type'=>Appointment::class,
                'pointable_id'=>$appointment->id,
                'history'=>Carbon::today(),
                'point_number'=>$point->point_number,
            ]);


            $session =SessionCenter::factory()->create([
                'sessionable_type'=>Appointment::class,
                'sessionable_id'=>$appointment->id,
            ]);

            $div=Division::where('doctor_id',$user->doctor->id)->get();
            for ($i=0 ;$i<3 ;$i++){
                Treatment::create([
                    'session_center_id'=>$session->id ,
                    'division_id'=>$div->random()->id,
                ]);

            }


        });







    }
}
