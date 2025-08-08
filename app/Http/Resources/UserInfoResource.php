<?php

namespace App\Http\Resources;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Http\Resources\Dashboards\AppointmentHomeCareResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    


    public function toArray(Request $request): array
    {
        return match ($request->route()->getName()) {
            'patient.index' => [
                'user_id'    => $this->id,
                'name_patient'=>$this->first_name.' '.$this->last_name,

                //'desc'  => $this->description,
                //'image' => $this->image_url,
            ],

            'doctors.index' => [
                'user_id'=>$this->id,
                'name_doctor'=>$this->first_name.' '.$this->last_name,
            ],

            'doctor.services'=>[
                'user_id_of_doctor'=>$this->user_id,
                'doctor_name'=>optional($this->doctor_user)->first_name.' '.optional($this->doctor_user)->last_name ,
                'doctor_id'=>$this->id,

            ],
            'nurse.patients'=> [
                $this->patients->map(function ($patient) {
                    return [
                        'patient_id'=>$patient?->id,
                        'name'=>$patient?->full_name,
                        'age'=>$patient?->patient_user->age,
                        'last_appointment'=>AppointmentHomeCareResource::appointment($patient?->appointment_homes()->where('status',AppointmentHomeCareStatus::Scheduled)->first()),
                        'next_appointment'=>$patient?->patient_user->next_appointment,
                    ];
                }),

            ],

            default => [
                'id'   => $this->id,
                //'name' => $this->name,
            ]
        };
    }
}
