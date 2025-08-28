<?php

namespace App\Http\Resources;

use App\Enums\Appointments\appointment\AppointmentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    protected $locale;
    protected $case;
    protected $rel;
    public function __construct($resource,$rel=null)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
        //$this->case=$case;
        $this->rel=$rel;
    }



    public function toArray(Request $request): array
    {
        return match ($this->rel){
            'Doctor'=> $this->ifDon(),
            'Secretary'=>$this->Secretary(),
              default => [],

        };
    }

    public function ifDon()
    {
        $return=[
            'id'=>$this->id,
            'date'=>$this->date,
            'time'=>$this->time,
            'status'=>$this->status,
            'mode'=>$this->appointment_type,
            'patent_name'=>optional($this->appointment_patient)->getFullNameAttribute(),
            'patient_num'=>optional($this->appointment_patient)->patient_num,
        ];
        if($this->status ==AppointmentStatus::Don){
            $return =array_merge($return,[
                'bill'=>$this->bill,
                'paid_bill'=>$this->paid_bill,
                'info session' =>$this->sesstions->map(function ($session) {
                    return [
                        'session_name'=>$session->session_name,
                        'diagnosis'=>$session->diagnosis,
                        'symptoms'=>$session->symptoms,
                        'medicines'=>$session->medicines,
                    ];
                })
            ]);

        }

        return $return ;
    }


    public function Secretary()
    {
        $return=[
            'id'=>$this->id,
            'date'=>$this->date,
            'time'=>$this->time,
            'status'=>$this->status,
            'mode'=>$this->appointment_type,
            'patient_name'=>optional($this->appointment_patient)->getFullNameAttribute(),
            'patient_num'=>optional($this->appointment_patient)->patient_num,
            'doctor_name'=>optional($this->appointment_doctor)->getFullNameAttribute(),
            'department'=>$this->department


        ];

        return $return;
    }


}
