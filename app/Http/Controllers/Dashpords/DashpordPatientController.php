<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionsResource;
use App\Services\Dashpords\DashpordPatientService;
use Illuminate\Http\Request;

class DashpordPatientController extends Controller
{
    //
    protected $dashpordPatientService;
    public function __construct(DashpordPatientService $dashpordPatientService)
    {
        $this->dashpordPatientService = $dashpordPatientService;
    }


    public function myDoctors()
    {

        $re = $this->dashpordPatientService->myDoctors();
        return response()->json($re);
    }

    public function sessions()
    {

        $re = $this->dashpordPatientService->Sessions();
        return SessionsResource::collection($re);
    }

    public function appointments()
    {

        $re1 =$this->dashpordPatientService->appointment_future();
        $re2 =$this->dashpordPatientService->appointment_don();

        $re3=$this->dashpordPatientService->appointment_hc_future();
        $re4=$this->dashpordPatientService->appointment_hc_don();

        return response()->json([
            'sessions' => true,
            'message'=>'success',
            'appointments' =>[
                'appointment_clinic'=> [
                    'future'=>$re1,
                    'don'=>$re2,
                ] ,
                'appointment_homeCare' =>[
                    'future'=>$re3,
                    'don'=>$re4
                ] ,
            ],
        ]);


    }

    public function allAppointments()
    {
        $re1 =$this->dashpordPatientService->all_app_clinic();
        $re2 =$this->dashpordPatientService->all_app_homeCare();

        return response()->json([
            'success' => true,
            'message'=>'success',
            'appointments' =>[
                'clinic'=>$re1,
                'homeCare'=>$re2,
            ]
        ]);

    }

    public function my_points()
    {
        return response()->json($this->dashpordPatientService->my_points());
    }


    public function evaluction(Request $request)
    {

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'number'=>'required|integer|between:1,5',
        ]);

        $re=$this->dashpordPatientService->evaluction($request);

        return response()->json($re);




    }





}
