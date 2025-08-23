<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DivisionStoreRequest;
use App\Http\Requests\Admin\DivisionUpdateRequest;
use App\Http\Resources\Admin\DivisionResource;
use App\Models\Division;
use App\Services\Admin\Sections\DivisionService;
use Illuminate\Http\Request;
use Throwable;

class DivisionController extends Controller
{
    public function __construct(private DivisionService $svc) {}

    public function index(Request $request)
    {
        $items = $this->svc->list(
            filters: [
                'doctor_id'       => $request->integer('doctor_id'),
                'small_service_id'=> $request->integer('small_service_id'),
                'section_id'      => $request->integer('section_id'),
                'is_discounted'   => $request->has('is_discounted') ? $request->boolean('is_discounted') : null,
                'sort'            => $request->string('sort', 'id'),
                'dir'             => $request->string('dir', 'desc'),
            ],
            perPage: $request->integer('per_page', 20)
        );

        return ApiResponse::success(DivisionResource::collection($items));
    }

    public function store(DivisionStoreRequest $request)
    {
        try {
            $item = $this->svc->create($request->validated());
            return ApiResponse::success(new DivisionResource($item), 'تمت الإضافة بنجاح', 201);
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function show(Division $division)
    {
        $division->load(['division_small_service.smallService_section','division_doctor'])
            ->loadCount('treatments');

        return ApiResponse::success(new DivisionResource($division));
    }

    public function update(DivisionUpdateRequest $request, Division $division)
    {
        try {
            $item = $this->svc->update($division, $request->validated());
            return ApiResponse::success(new DivisionResource($item), 'تم التحديث بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(Division $division)
    {
        try {
            $this->svc->delete($division);
            return ApiResponse::success(null, 'تم الحذف بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    // (اختياري) تفعيل/تعطيل الخصم بسرعة
    public function toggleDiscount(Request $request, Division $division)
    {
        $data = $request->validate([
            'is_discounted' => ['required','boolean'],
            'discount_rate' => ['nullable','numeric','min:0','max:100'],
        ]);

        $item = $this->svc->toggleDiscount($division, $data['is_discounted'], $data['discount_rate'] ?? 0);
        return ApiResponse::success(new DivisionResource($item), 'تم تحديث الخصم');
    }
}
