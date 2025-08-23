<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SmallServiceStoreRequest;
use App\Http\Requests\Admin\SmallServiceUpdateRequest;
use App\Http\Resources\Admin\SmallServiceResource;
use App\Models\SmallService;
use App\Services\Admin\Sections\SmallServiceService;
use Illuminate\Http\Request;
use Throwable;

class SmallServiceController extends Controller
{
    public function __construct(private SmallServiceService $svc) {}

    public function index(Request $request)
    {
        $items = $this->svc->list(
            filters: [
                'section_id' => $request->integer('section_id'),
                'search'     => $request->string('search'),
                'sort'       => $request->string('sort', 'id'),
                'dir'        => $request->string('dir', 'desc'),
            ],
            perPage: $request->integer('per_page', 20)
        );

        return ApiResponse::success(SmallServiceResource::collection($items));
    }

    public function store(SmallServiceStoreRequest $request)
    {
        try {
            $item = $this->svc->create($request->validated());
            return ApiResponse::success(new SmallServiceResource($item), 'تمت الإضافة بنجاح', 201);
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function show(SmallService $smallService)
    {
        $smallService->load(['smallService_section'])->loadCount(['divisions','whatPhotos']);
        return ApiResponse::success(new SmallServiceResource($smallService));
    }

    public function update(SmallServiceUpdateRequest $request, SmallService $smallService)
    {
        try {
            $item = $this->svc->update($smallService, $request->validated());
            return ApiResponse::success(new SmallServiceResource($item), 'تم التحديث بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(SmallService $smallService)
    {
        try {
            $this->svc->delete($smallService);
            return ApiResponse::success(null, 'تم الحذف بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }
}
