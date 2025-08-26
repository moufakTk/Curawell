<?php

namespace App\Services\Dashpords;


use App\Enums\Appointments\appointment\AppointmentType;
use App\Enums\Sessions\SessionDoctorStatus;
use App\Enums\Users\UserType;
use App\Events\WhatsAppTaxi;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\OrderTaxi;
use App\Models\Point;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Carbon;

class ForAllService
{

    protected $dashpordPatientService;
    protected $dashpordDoctorService;
    protected $dashpordNurseService;

    public function __construct(DashpordPatientService $dashpordPatientService , DashpordDoctorService $dashpordDoctorService , DashpordNurseService $dashpordNurseService){
        $this->dashpordPatientService = $dashpordPatientService;
        $this->dashpordDoctorService = $dashpordDoctorService;
        $this->dashpordNurseService = $dashpordNurseService;
    }


    public function profile()
    {

        $user= auth()->user();
 if (!$user)
     return response()->json(['error' => 'asdasdaskdljasdkl'], 401);
        return match ($user->user_type){

            UserType::Patient => $this->dashpordPatientService->profilePatient($user),
            UserType::Doctor => $this->dashpordDoctorService->profileDoctor($user),
            UserType::Nurse=>$this->dashpordNurseService->profileNurse($user),
            default => abort(404),
        };



    }


    public function test_doctor_session($doctor_session)
    {
        $bool =false;
        if(in_array($doctor_session->status ,[SessionDoctorStatus::UnAvailable ,sessionDoctorStatus::Reserved ,SessionDoctorStatus::TurnOff]))
        {
            $bool = true;
        }
        return $bool;
    }


    public function check_doctor_session($doctor_session,$doctor)
    {
        $bool =false;
        if(!($doctor_session->session_doctor->user_id === $doctor->doctor_user->id)){
            $bool = true;
        }

        return $bool;
    }



    public function check_given_point($appointment)
    {

        $doctor_session=$appointment->appointment_doctor_session;
        $patient=$appointment->appointment_patient;

        if($appointment->appointment_type ===AppointmentType::Electronically)
        {
            $point = Point::where('name_en','Book an appointment online')->first();

            $userPoint=UserPoint::create([
                'patient_id'=>$patient->id,
                'point_id'=>$point->id ,
                'pointable_type'=>Appointment::class,
                'pointable_id'=>$appointment->id,
                'history'=>Carbon::today(),
                'point_number'=>$point->point_number,
            ]);

            $patient->update(["totalPoints"=>$patient->totalPoints + $userPoint->point_number]);
        }


        if($appointment->delivery == true){

            $point=Point::where('name_en','Request delivery service online')->first();
            $userPoint=UserPoint::create([
                'patient_id'=>$patient->id,
                'point_id'=>$point->id ,
                'pointable_type'=>Appointment::class,
                'pointable_id'=>$appointment->id,
                'history'=>Carbon::today(),
                'point_number'=>$point->point_number,
            ]);
            $patient->update(["totalPoints"=>$patient->totalPoints + $userPoint->point_number]);
        }

    }

    public function check_given_point_hc($appointment)
    {
        $patient =$appointment->appointment_home_patient;

        $point=Point::where('name_en','Request home service online')->first();
        $userPoint=UserPoint::create([
            'patient_id'=>$patient->id,
            'point_id'=>$point->id ,
            'pointable_type'=>AppointmentHomeCare::class,
            'pointable_id'=>$appointment->id,
            'history'=>Carbon::today(),
            'point_number'=>$point->point_number,
        ]);
        $patient->update(["totalPoints"=>$patient->totalPoints + $userPoint->point_number]);

    }


}
