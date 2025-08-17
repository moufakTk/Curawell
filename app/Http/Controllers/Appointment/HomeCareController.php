<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodHomeCareResource;
use App\Services\Appointment\HomeCareService;
use Illuminate\Http\Request;

class HomeCareController extends Controller
{
    //
    protected $homeCareService;

    public function __construct(HomeCareService $homeCareService){
        $this->homeCareService = $homeCareService;
    }


    public function services(Request $request)
    {

        $request->validate([
            'section_id'=>'required|exists:sections,id',
        ]);

        return response()->json($this->homeCareService->services($request));

    }

    public function nurseHomeCare()
    {
        return response()->json($this->homeCareService->nurseHomeCare());
    }

    public function periodsHomeCare(Request $request)
    {

        $request->validate([
            'date'=>'required|date_format:Y-m-d',
        ]);



        $re = $this->homeCareService->periodsHomeCare($request);

        if(!$re){
            return [
                'success'=>false,
                'message'=>__('messages.date_not_found'),
                'data'=>[]
            ];

        }
        return [
            'success'=>true,
            'message'=>__('messages.period_found'),
            'data'=>new PeriodHomeCareResource($re)
        ];

    }

    public function reserveAppointmentHomeCare(Request $request)
    {
        $request->validate([
            'period_id'=>'required|exists:period_home_cares,id',
            'location'=>'required|string',
            'phone'=>'required|string',
            'service_type'=>'required|string|in:General Medical Checkup,Physical Therapy,Sample Collection',
            'gender'=>'required|string|in:male,female',
            'date'=>'required|date_format:Y-m-d',
            'note'=>'nullable|string'

        ]);



        return response()->json($this->homeCareService->reserveAppointmentHomeCare($request));

    }

}
