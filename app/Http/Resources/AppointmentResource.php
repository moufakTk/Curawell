<?php

namespace App\Http\Resources;

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
    public function __construct($resource ,$case)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
        $this->case=$case;
    }


    public function toArray(Request $request): array
    {

        return match ($this->case) {

            'HomeCare'=>$this->appointment_homeCare(),

            'Clinic'=>$this->appointment_clinic(),

        };

    }


    public function appointment_clinic():array
    {
        return [
            'date'=>$this->date,
            'time'=>$this->time,
            'type'=>$this->type_serv,
            'department'=>$this->department,
            'status'=>$this->status,
            'doctor'=>$this->appointment_doctor->doctor_user->getFullNameAttribute(),
        ];
    }

    public function appointment_homeCare():array
    {
        return [
            "date"=>$this->appointment_home_session_nurse->session_day->history,
            'time'=>$this->appointment_home_session_nurse->time_in->format('H:i:s'),
            'type'=>$this->type_serv,
            'department'=>$this->type,
            'status'=>$this->status,
            'nurse'=>$this->appointment_home_session_nurse->nurse->getFullNameAttribute(),

        ];
    }

}
