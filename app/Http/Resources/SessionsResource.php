<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionsResource extends JsonResource
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
        return [


            'time_ago'=>$this->date_ago,
            'date'=>$this->date_appointment,
            'doctor_name'=>$this->doctor_name,
            'info_session'=>$this->sesstions->map(function ($session) {
                return [
                    'session_id'=>$session->id,
                    'session_name'=>$session->session_name,
                    'diagnosis'=>$session->diagnosis,
                    'symptoms'=>$session->symptoms,
                    'medicines'=>$session->medicines,

                ];
            }),

//            "medicines_for_all"=>[
//                'date'=>$this->date_appointment,
//                'medicines'=>$this->sesstions->map(function ($session) {
//                    return $session->medicines;
//                })
//            ]

        ];
    }



}
