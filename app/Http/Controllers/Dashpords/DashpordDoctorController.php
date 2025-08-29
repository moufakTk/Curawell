<?php

namespace App\Http\Controllers\Dashpords;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SessionInfoRequest;
use App\Models\Doctor;
use App\Models\DoctorEdit;
use App\Models\Patient;
use App\Models\SessionCenter;
use App\Services\Dashpords\DashpordDoctorService;
use http\Env\Response;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class DashpordDoctorController extends Controller
{
    //
    protected $dashpordDoctorService;

    public function __construct(DashpordDoctorService $dashpordDoctorService){
        $this->dashpordDoctorService = $dashpordDoctorService;
    }

    public function treatments(){


        $re = $this->dashpordDoctorService->treatments();

        return response()->json([
            'success'=>true,
            'message'=>'هي كل العلاجلت',
            "data"=>$re,
        ]);

    }
    public function addTreatmentTOSession(Request $request)
    {

        $request->validate([
            'session_id' => 'required|exists:session_centers,id',
            'with_exam'=>'required|boolean',
            'treatments'=>'required|array|min:1',
            'treatments.*.treatment_id'=>'required|integer|exists:divisions,id',
            'treatments.*.quantity'=>'required|integer|min:1',
        ]);

        $re =$this->dashpordDoctorService->addTreatmentTOSession($request);
        return response()->json($re);
    }
    public function addEdit(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:session_centers,id',
            'price' =>"required|numeric",
            'edit'=>'required|string',
        ]);

        $re = $this->dashpordDoctorService->addEdit($request);
        return response()->json($re);

    }
    public function updateEdit(Request $request)
    {

        $request->validate([
            'doctorEdit_id' => 'required|exists:doctor_edits,id',
            'price' =>"required|numeric",
            'edit'=>'required|string',
        ]);

        $re = $this->dashpordDoctorService->updateEdit($request);
        return response()->json($re);
    }
    public function deleteEdit(Request $request)
    {
        $request->validate(['doctorEdit_id'=>'required|exists:doctor_edits,id']);

        $re = $this->dashpordDoctorService->deleteEdit($request);
        return response()->json($re);
    }
    public function reserved_sessions()
    {

        $re=$this->dashpordDoctorService->reserved_sessions();

        return response()->json($re);
    }
    public function num_all_patients(){
        $re=$this->dashpordDoctorService->num_all_patients();
        return response()->json($re);
    }
    public function appointments_occur()
    {
        $re=$this->dashpordDoctorService->appointments_occur();
        return response()->json($re);
    }

    public function doctor_patients(Doctor $doctor=null)
    {
        try {

            $user = auth()->user();

            if ($doctor) {
                if (!$user->hasRole('Secretary')) {
                    throw new \Exception(__('messages.auth.unauthorized'), 403);
                }
            }

            else {

                if (!$user->hasRole('Doctor_clinic') ) {
                    throw new \Exception(__('messages.auth.unauthorized'), 403);
                }
            }

            $re=$this->dashpordDoctorService->doctor_patients($doctor);
            return response()->json($re);

        }catch (\Exception $exception){
            return ApiResponse::error([],$exception->getMessage(),$exception->getCode() == 0 | null ? 500 : $exception->getCode());
        }


    }

    public function all_appointments_doctor()
    {
        $re =$this->dashpordDoctorService->all_appointments_doctor();
        return response()->json($re);
    }

    public function number_appointment()
    {
        $re =$this->dashpordDoctorService->number_appointment();
        return response()->json($re);
    }


    public function appointment_doctor_patient(Patient $patient)
    {

        $re =$this->dashpordDoctorService->appointment_doctor_patient($patient);
        return response()->json($re);

    }


    public function add_info_session(SessionInfoRequest $request)
    {
        $re =$this->dashpordDoctorService->add_info_session($request);
        return response()->json($re);

    }

}
