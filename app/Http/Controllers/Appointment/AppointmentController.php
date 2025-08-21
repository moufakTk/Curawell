<?php

namespace App\Http\Controllers\Appointment;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Http\Resources\CompetencesResource;
use App\Services\Appointment\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService){
        $this->appointmentService = $appointmentService;
    }

    public function getDoctorServices(Request $request){

        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);
        $re = $this->appointmentService->getDoctorServices($request);
        return CompetencesResource::collection($re);

    }
    public function competences(Request $request)
    {

        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);
        $re = $this->appointmentService->competences($request);
        return CompetencesResource::collection($re);

    }

    public function competenceDoctors(Request $request)
    {

        $request->validate([
            'competence_id'=>'nullable|exists:competences,id',
            'service_id'=>'nullable|exists:services,id',
            'date'=>'required|date',
        ]);
        $re = $this->appointmentService->competenceDoctors($request);
        return response()->json($re);

    }

    public function doctorStatus(Request $request)
    {

        $request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'date'=>'required|date_format:Y-m-d',
        ]);
        $re = $this->appointmentService->doctorStatus($request);
        return response()->json($re);

    }
    public function serviceOffers(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        if($this->appointmentService->serviceOffers($request)->isEmpty()){
            return response()->json([
                'success'=>'false',
                "message"=>__('messages.discount_not_exist'),
                "data"=>[]
            ]);
        }
        return response()->json([
            'success'=>'true',
            'message'=>__('messages.discount_exist'),
            "data"=>$this->appointmentService->serviceOffers($request),
        ]);
    }

    public function dayAndSession(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_date' => 'required|date|exists:work_days,history',
        ]);

        return response()->json($this->appointmentService->dayAndSession($request));

    }

    public function reserveAppointment(AppointmentRequest $request)
    {

        try {
        $user=auth()->user();

        if($request->mode=='FaceToFace'){
            if(!$user->hasRole('Secretary'))
            {
                throw new \Exception(__('messages.auth.unauthorized'), 403);
            }
        }else{
            if(!$user->hasRole('Patient')){
                throw new \Exception(__('messages.auth.unauthorized'), 403);
            }
        }

        return response()->json($this->appointmentService->reserveAppointment($request));

        }catch (\Exception $exception){
            return ApiResponse::error([],$exception->getMessage(),$exception->getCode() == 0 | null ? 500 : $exception->getCode());
        }
    }






}
