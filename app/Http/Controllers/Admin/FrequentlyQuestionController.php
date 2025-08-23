<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FrequentlyQuestionRequest;
use App\Http\Resources\Admin\FrequentlyQuestionResource;
use App\Models\FrequentlyQuestion;
use App\Services\Admin\FrequentlyQuestion\FrequentlyQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrequentlyQuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $query   = FrequentlyQuestion::query();

        if ($request->has('status')) {
            $query->where('status', (bool) $request->query('status'));
        }

        $paginator = $query->orderByDesc('id')->paginate($perPage);

        return ApiResponse::success(
            FrequentlyQuestionResource::collection($paginator)->response()->getData(true),
            'قائمة الأسئلة الشائعة'
        );
    }

    public function store(FrequentlyQuestionRequest $request, FrequentlyQuestionService $service): JsonResponse
    {
        $fq = $service->create($request->validated());

        return ApiResponse::success(
            new FrequentlyQuestionResource($fq),
            'تم إنشاء السؤال بنجاح',
            201
        );
    }

    public function show(FrequentlyQuestion $frequentlyQuestion): JsonResponse
    {
        return ApiResponse::success(
            new FrequentlyQuestionResource($frequentlyQuestion),
            'تفاصيل السؤال'
        );
    }

    public function update(FrequentlyQuestionRequest $request, FrequentlyQuestion $frequentlyQuestion, FrequentlyQuestionService $service): JsonResponse
    {
        $fq = $service->update($frequentlyQuestion, $request->validated());

        return ApiResponse::success(
            new FrequentlyQuestionResource($fq),
            'تم تعديل السؤال بنجاح'
        );
    }

    public function destroy(FrequentlyQuestion $frequentlyQuestion, FrequentlyQuestionService $service): JsonResponse
    {
        $service->delete($frequentlyQuestion);

        return ApiResponse::success(null, 'تم حذف السؤال بنجاح');
    }

    public function toggle(FrequentlyQuestion $frequentlyQuestion): JsonResponse
    {
        $frequentlyQuestion->status = ! $frequentlyQuestion->status;
        $frequentlyQuestion->save();

        return ApiResponse::success(
            new FrequentlyQuestionResource($frequentlyQuestion),
            'تم تغيير حالة التفعيل'
        );
    }
}
