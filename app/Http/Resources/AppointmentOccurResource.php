<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentOccurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $locale ;
    protected $case ;
    public function __construct($resource,$case)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
        $this->case=$case;
    }

    public function toArray(Request $request): array
    {
        return match ($this->case){

            'Waiting'=>[
                'appointment_id'=>$this->id,
                'session_id'=>optional($this->sesstions->first())->id,
                'kind'=>$this->kind,
                'name_doctor'=>optional($this->waiting_doctor)->getFullNameAttribute(),
                'department'=>$this->department_doctor,
                'doctor_photo'=>'',
                'patient_name'=>optional($this->waiting_patient)->getFullNameAttribute(),
                'patient_num'=>optional($this->waiting_patient)->patient_num,
                'patient_photo'=>'',
                'phone_number'=>$this->phone_number,
                'status'=>$this->status,
                'type'=>$this->waiting_type,
            ],

            'Appointment'=>[
                'appointment_id'=>optional($this->appointments)->id,
                'session_id'=>$this->id,
                'kind'=>$this->kind,
                'name_doctor'=>optional(optional($this->appointments)->appointment_doctor)->getFullNameAttribute(),
                'department'=>$this->department_doctor,
                'doctor_photo'=>'',
                'patient_name'=>optional(optional($this->appointments)->appointment_patient)->getFullNameAttribute(),
                'patient_num'=>optional(optional($this->appointments)->appointment_patient)->patient_num,
                'patient_photo'=>'',
                'phone_number'=>optional($this->appointments)->phone_number,
                'status'=>optional($this->appointments)->status,
                'type'=>optional($this->appointments)->appointment_type,

            ],

            'Appointment_checkOut'=>[

                'appointment_id'=>optional($this->appointments)->id,
                'session_id'=>$this->id,
                'kind'=>$this->kind,
                'name_doctor'=>optional(optional($this->appointments)->appointment_doctor)->getFullNameAttribute(),
                'department'=>$this->department_doctor,
                'doctor_photo'=>'',
                'patient_name'=>optional(optional($this->appointments)->appointment_patient)->getFullNameAttribute(),
                'patient_num'=>optional(optional($this->appointments)->appointment_patient)->patient_num,
                'patient_photo'=>'',
                'phone_number'=>optional($this->appointments)->phone_number,
                'status'=>optional($this->appointments)->status,
                'type'=>optional($this->appointments)->appointment_type,

                'appointment_bill'=>optional(optional($this->appointments)->appointment_bills->first())->total_treatment_amount,
                'paid_bill'=>optional(optional($this->appointments)->appointment_bills->first())->paid_of_amount,

            ],

            'Waiting_checkOut'=>[
                'appointment_id'=>$this->id,
                'session_id'=>optional($this->sesstions->first())->id,
                'kind'=>$this->kind,
                'name_doctor'=>optional($this->waiting_doctor)->getFullNameAttribute(),
                'department'=>$this->department_doctor,
                'doctor_photo'=>'',
                'patient_name'=>optional($this->waiting_patient)->getFullNameAttribute(),
                'patient_num'=>optional($this->waiting_patient)->patient_num,
                'patient_photo'=>'',
                'phone_number'=>$this->phone_number,
                'status'=>$this->status,
                'type'=>$this->waiting_type,
                'appointment_bill'=>optional($this->appointment_bills->first())->total_treatment_amount,
                'paid_bill'=>optional($this->appointment_bills->first())->paid_of_amount,
            ],

            default =>[],

        };
    }
}
