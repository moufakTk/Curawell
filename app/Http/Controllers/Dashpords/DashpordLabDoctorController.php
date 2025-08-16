<?php

namespace App\Http\Controllers\Dashpords;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AnalyzeOrder;
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

    public function pendingAnalyses()
    {
        try {
            $data = $this->dashpordLabDoctorService->pendingAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }

    }
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
