<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaitingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $locale;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();

    }



    public function toArray(Request $request): array
    {
        return[

            'appointment_id'=>$this->id,
            'session_id'=>optional($this->sesstions->first())->id,
            'phone_number'=>$this->phone_number,
            'status'=>$this->status,
            'waiting_type'=>$this->waiting_type,
            'patient_name'=>optional($this->waiting_patient)->getFullNameAttribute(),
            'patient_num'=>optional($this->waiting_patient)->patient_num,
            'patient_photo'=>''


        ];
    }
}
