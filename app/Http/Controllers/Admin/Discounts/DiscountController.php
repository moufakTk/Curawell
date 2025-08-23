<?php

namespace App\Http\Controllers\Admin\Discounts;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\Discounts\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index(){}
    public function create(){}
    public function update(Request $request){}
    public function delete(Request $request){}
    public function show(Request $request){}

    public function searchDoctors(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
        ]);

        try {
            $data = $this->discountService->searchDoctors($request->name);
            return ApiResponse::success($data, 'all doctors', 200);
        } catch (\Exception $exception) {
            // كان ناقص return
            return ApiResponse::error([], $exception->getMessage(), 500);
        }
    }

    public function getDoctorsServices(Request $request)
    {
        $request->validate([
            'doctor_ids'   => 'required|array|min:1',
            'doctor_ids.*' => 'integer|exists:doctors,id',
        ]);

        try {
            $data = $this->discountService->getDoctorsServices($request);
            return ApiResponse::success($data, 'all services', 200);
        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), 500);
        }
    }

}
