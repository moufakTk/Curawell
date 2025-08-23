<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompetenceStoreRequest;
use App\Http\Requests\Admin\CompetenceUpdateRequest;
use App\Http\Resources\Admin\CompetenceResource;
use App\Models\Competence;
use App\Services\Admin\Sections\CompetenceService;
use Illuminate\Http\Request;
use Throwable;

class CompetenceController extends Controller
{
    public function __construct(private CompetenceService $svc) {}

    public function index(Request $request)
    {
        $items = $this->svc->list(
            filters: [
                'service_id' => $request->integer('service_id'),
                'search'     => $request->string('search'),
            ],
            perPage: $request->integer('per_page', 20)
        );

        return ApiResponse::success(CompetenceResource::collection($items));
    }

    public function store(CompetenceStoreRequest $request)
    {
        try {
            $item = $this->svc->create($request->validated());
            return ApiResponse::success(new CompetenceResource($item), 'تمت الإضافة بنجاح', 201);
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function show(Competence $competence)
    {
        $competence->load(['competence_services.service_section']);
        return ApiResponse::success(new CompetenceResource($competence));
    }

    public function update(CompetenceUpdateRequest $request, Competence $competence)
    {
        try {
            $item = $this->svc->update($competence, $request->validated());
            return ApiResponse::success(new CompetenceResource($item), 'تم التحديث بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(Competence $competence)
    {
        try {
            $this->svc->delete($competence);
            return ApiResponse::success(null, 'تم الحذف بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }
}
