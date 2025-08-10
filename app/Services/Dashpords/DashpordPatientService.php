<?php

namespace App\Services\Dashpords;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\Competence;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class DashpordPatientService
{

    protected $locale;
    public function __construct(){
        $this->locale = App::getLocale();
    }


    public function profilePatient(User $user)
    {
        return $user->load('patient.medical_history');
    }

    public function myDoctors()
    {
        $patient =auth()->user()->patient;

        $minIds = Appointment::where('patient_id', $patient->id)
            ->where('status', '!=', AppointmentStatus::Cancel)
            ->selectRaw('MIN(id) as id')
            ->groupBy('doctor_id');

        $appointments = Appointment::whereIn('id', $minIds)
            ->with('appointment_doctor.doctor_user.active_work_location')
            ->get();

        $appointments->each(function ($appointment) {
            $d =$appointment->appointment_doctor->doctor_user->active_work_location;

            $c=Competence::where('id',$d->locationable_id)->value('name_'.$this->locale);
            $appointment->appointment_doctor->doctor_user->competence_name =$c;

        });

        return $appointments->pluck('appointment_doctor.doctor_user')->map(function ($doctor) {
            $doctor->makeHidden(['active_work_location']);
            return $doctor->only(['id', 'first_name', 'last_name', 'competence_name']);
        });

    }

    public function sessions()
    {

        $appointments= Appointment::where('status',AppointmentStatus::Don)->whereHas('appointment_patient' ,function ($q){
            $q->where('id',auth()->user()->patient->id);
        })->with(['sesstions' => function ($query) {
            $query->where('sessionable_type', Appointment::class)
                ->with('treatments');
        }])
        ->get()->map(function ($appointment) {

            $date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
            $time =$appointment->appointment_doctor_session->from;
            $sessionDateTime = Carbon::parse("$date $time");

            $diffInSeconds = now()->diffInSeconds($sessionDateTime);
            $days = floor($diffInSeconds / 86400);
            $hours = floor(($diffInSeconds % 86400) / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);
            $appointment->date_ago ='d:'.$days.'|h:'.$hours.'|m:'.$minutes;
            $appointment->date_appointment=$sessionDateTime->format('Y-m-d H:i:s');

            return $appointment;

        })->sortBy('date_appointment');

        return $appointments;
    }

    public function appointment_future()
    {
        $appointment =Appointment::where('patient_id',auth()->user()->patient->id)
            ->whereIn('status',[AppointmentStatus::Confirmed ,AppointmentStatus::Occur])
            ->with('appointment_doctor.doctor_user' )
            ->get()
            ->each(function ($appointment) {

                    $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
                    $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
                    $appointment->department=$competence_name;

                    $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
                    $appointment->time =$appointment->appointment_doctor_session->from;
                    $appointment->type_serv ='Clinic';
            });

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'Clinic');
        });

    }

    public function appointment_don()
    {
        $appointment =Appointment::where(['patient_id'=>auth()->user()->patient->id ,'status'=>AppointmentStatus::Don])
            ->with('appointment_doctor.doctor_user' )
            ->get()->each(function ($appointment) {

                $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
                $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
                $appointment->department=$competence_name;

                $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
                $appointment->time =$appointment->appointment_doctor_session->from;
                $appointment->type_serv ='Clinic';
            });;

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'Clinic');
        });
    }

    public function appointment_hc_future()
    {

        $appointment =AppointmentHomeCare::where('patient_id',auth()->user()->patient->id)
            ->whereIn('status',[AppointmentHomeCareStatus::Scheduled])
            ->with('appointment_home_session_nurse.nurse','appointment_home_session_nurse.session_day')
            ->get()
            ->each(function ($q){
                $q->type_serv ='HomeCare';
            });

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'HomeCare');
        });
    }

    public function appointment_hc_don()
    {

        $appointment =AppointmentHomeCare::where(['patient_id'=>auth()->user()->patient->id,'status'=>AppointmentHomeCareStatus::Completed])
            ->with('appointment_home_session_nurse.nurse','appointment_home_session_nurse.session_day')
            ->get()
            ->each(function ($q){
                $q->type_serv ='HomeCare';
            });
        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'HomeCare');
        });

    }









}
