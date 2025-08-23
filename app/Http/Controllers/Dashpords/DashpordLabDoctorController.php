<?php

namespace App\Http\Controllers\Dashpords;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AnalyzeOrder;
use App\Models\Patient;
use App\Services\Dashpords\DashpordLabDoctorService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashpordLabDoctorController extends Controller

{
    protected $dashpordLabDoctorService;

    public function __construct(DashpordLabDoctorService $dashpordLabDoctorService)
    {
        $this->dashpordLabDoctorService = $dashpordLabDoctorService;
    }



    public function patientAnalyses(Patient $patient=null)
    {
        try {
            if (!$patient) {
                $user = auth()->user(); // يلي معه التوكن الحالي
                if (!$user->hasRole('Patient')) {
                    throw new \Exception('You must be a patient to access your own analyses', 403);
                }
                $patient = $user->patient; // العلاقة One-to-One بين user و patient
            } else {
                // إذا في باراميتر → لازم يكون المستخدم الحالي Doctor_lab
                if (!auth()->user()->hasRole('Doctor_lab')) {
                    throw new \Exception('Only lab doctors can access patient analyses', 403);
                }
            }

            $data = $this->dashpordLabDoctorService->patientAnalyses($patient);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }
    public function pendingAnalyses()
    {
        try {
            $data = $this->dashpordLabDoctorService->pendingAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }
//    public function patientAnalyses(Patient $patient)
//    {
//        try {
//            $data = $this->dashpordLabDoctorService->patientAnalyses($patient);
//            return ApiResponse::success($data['data'], $data['message'], 200);
//
//        } catch (\Exception $exception) {
//            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
//        }
//
//    }
    public function Analyses()
    {
        try {
            $data = $this->dashpordLabDoctorService->Analyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }
    public function completeAnalyses()
    {
        try {
            $data = $this->dashpordLabDoctorService->completedAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }
    public function countAnalyses()
    {
        try {
            $data = $this->dashpordLabDoctorService->countAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }


    public function updateAnalyses(AnalyzeOrder $analyzeOrder,Request $request)
    {
        $request->validate([
            'status'=>['nullable',Rule::enum(AnalyzeOrderStatus::class)],
            'reports' => ['nullable', 'array'],
            'reports.*' => ['nullable', 'file', 'mimes:pdf,png,jpg', 'max:5120'],
            'deleted_reports' => ['nullable', 'array'],
            'deleted_reports.*'=> ['nullable', Rule::exists('reports', 'id')],

        ]);
        try {

            $data = $this->dashpordLabDoctorService->updateAnalyses($analyzeOrder,$request);
            return ApiResponse::success($data['data'], $data['message'], 200);
        }catch (\Exception $exception){
            return ApiResponse::error([],$exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }



    }




}
