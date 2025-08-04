<?php

namespace App\Services\Dashpords;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Competence;
use App\Models\User;
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


}
