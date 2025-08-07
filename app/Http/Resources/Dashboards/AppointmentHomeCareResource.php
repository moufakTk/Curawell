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
//            'nurse.appointments.grouped' => $this->formatForGrouped(),
            'nurse.show.session' =>   [
        'id'         => $appointment?->id,
        'session_id' => $appointment?->nurse_session_id,
        'patient'    => optional($appointment?->appointment_home_patient?->patient_user)->full_name
            ?? 'Unknown Patient',
        'contact'    => $appointment?->phone_number,
        'address'    => $appointment?->location,
        'service'    => $appointment?->type,
        'notes'=> $appointment?->note,
        'cost'       => $appointment?->price,
        'report'     => $appointment?->explain,
        'day' => $this->session_day?->{'day_' . app()->getLocale()},
        'history' => $this->session_day?->history,
        'time'=>$this?->time_in->format('h:i A'),
//            'message'    => $appointment? 'this session is available':'this session is available'
    ],
//            default => $this->formatDefault(),
        };
    }


}
