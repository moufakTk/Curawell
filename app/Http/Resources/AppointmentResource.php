<?php

namespace App\Http\Resources;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Enums\Appointments\appointment\AppointmentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    protected $locale;
    protected $case;
    protected $rel;
    public function __construct($resource ,$case=null ,$rel=null)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
        $this->case=$case;
        $this->rel=$rel;
    }


    public function toArray(Request $request): array
    {

        return match ($request->route()->getName()) {

            'patient_appointments'=>match ($this->case) {

                'HomeCare'=>$this->appointment_homeCare($this->rel),


                'Clinic'=> $this->appointment_clinic($this->rel),

            },

            'doctor_appointments'=>$this->appointment_for_doctor(),


        };


    }


    public function appointment_clinic($rel):array
    {

        $return=[
            'date'=>$this->date,
            'time'=>$this->time,
            'type'=>$this->type_serv,
            'department'=>$this->department,
            'status'=>$this->status,
            'mode'=>$this->appointment_type,
            'doctor'=>$this->appointment_doctor->doctor_user->getFullNameAttribute(),
        ];

        if($rel && $this->status ==AppointmentStatus::Don ){

            $return =array_merge($return,[
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

    public function appointment_homeCare($rel):array
    {

        $return =[
            "date"=>$this->appointment_home_session_nurse->session_day->history,
            'time'=>$this->appointment_home_session_nurse->time_in->format('H:i:s'),
            'type'=>$this->type_serv,
            'department'=>$this->type,
            'status'=>$this->status,
            'mode'=>$this->style,
            'nurse'=>$this->appointment_home_session_nurse->nurse->getFullNameAttribute(),

        ];

        if($rel && $this->status == AppointmentHomeCareStatus::Completed){
            $return =array_merge($return,[
                'info session'=>[
                    'your_phone_number'=>$this->phone_number,
                    'notes'=>$this->notes,
                    'price'=>$this->price,
                    'diagnoses'=>$this->explain
                ]
            ]);
        }

        return $return ;
    }



    public function appointment_for_doctor()
    {

        return [

            'time'=>$this->from,
            'phone_number'=>optional($this->appointments)->phone_number,
            'status'=>optional($this->appointments)->status,
            'type'=>optional($this->appointments)->appointment_type,
            'patient_num'=>optional(optional($this->appointments)->appointment_patient)->patient_num,
            'patient name'=> optional(optional(optional($this->appointments)->appointment_patient)->patient_user)->getFullNameAttribute(),
            'patient_photo'=>'',


        ];

    }

}
