<?php

namespace App\Http\Resources\Dashboards;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentHomeCareResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $routeName = $request->route()?->getName(); // ممكن تكون null، فاستعمل ?->
        $appointment = $this->appointments_home;

        return match ($routeName)  {
            'nurse.show.session' =>   [
        'id'         => $appointment?->id,
        'session_id' => $appointment?->nurse_session_id,
        'patient'    => optional($appointment?->appointment_home_patient?->patient_user)->full_name
            ?? 'Unknown Patient',
        'contact'    => $appointment?->phone_number,
        'address'    => $appointment?->location,
        'service'    => $appointment?->type,
        'notes'=> $appointment?->notes,
        'cost'       => $appointment?->price,
        'report'     => $appointment?->explain,
        'day' => $this->session_day?->{'day_' . app()->getLocale()},
        'history' => $this->session_day?->history,
        'time'=>$this?->time_in->format('h:i A'),
//            'message'    => $appointment? 'this session is available':'this session is available'
    ],
            'nurse.appointments' => [
                'id'         => $this?->id,
                'session_id' => $this?->nurse_session_id,
                'patient'    => optional($this?->appointment_home_patient?->patient_user)->full_name
                    ?? 'Unknown Patient',
                'service'    => $this?->type,
                'day' => $this?->appointment_home_session_nurse?->session_day?->{'day_' . app()->getLocale()},
                'history' => $this?->appointment_home_session_nurse?->session_day?->history,
                'time'=>$this?->appointment_home_session_nurse?->time_in->format('h:i A'),

            ],
            'nurse.completed.appointments' =>[
                 'id'         => $this?->id,
                 'session_id' => $this?->nurse_session_id,
                 'patient'    => optional($this?->appointment_home_patient?->patient_user)->full_name
                    ?? 'Unknown Patient',
                 'service'    => $this?->type,
                'contact'    => $this?->phone_number,

                'notes'=> $this?->notes,
                'status'=> $this?->status,
                'cost'       => $this?->price,
                'report'     => $this?->explain,
                 'day' => $this?->appointment_home_session_nurse?->session_day?->{'day_' . app()->getLocale()},
                 'history' => $this?->appointment_home_session_nurse?->session_day?->history,
                 'time'=>$this?->appointment_home_session_nurse?->time_in->format('h:i A'),

            ]

        };
    }

public  static  function appointment($appointment): array{
        return $appointment?[
            'id'         => $appointment?->id,
            'session_id' => $appointment?->nurse_session_id,
//            'patient'    => optional($appointment?->appointment_home_patient?->patient_user)->full_name
//                ?? 'Unknown Patient',
            'service'    => $appointment?->type,
            'day' => $appointment?->appointment_home_session_nurse?->session_day?->{'day_' . app()->getLocale()},
            'history' => $appointment?->appointment_home_session_nurse?->session_day?->history,
            'time'=>$appointment?->appointment_home_session_nurse?->time_in->format('h:i A'),
            'contact'    => $appointment?->phone_number,
            'address'    => $appointment?->location,
        ]:[];
}
}
