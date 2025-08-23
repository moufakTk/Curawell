<?php

namespace App\Http\Controllers\Admin\Articles;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Requests\Admin\ArticleUpdateRequest;
use App\Http\Resources\Admin\Articles\ArticleResource;
use App\Models\Article;
use App\Services\Admin\Articles\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q        = $request->string('q')->toString();
        $isActive = $request->has('is_active') ? (int) $request->query('is_active') : null;
        $perPage  = (int) $request->query('per_page', 15);
        $sort     = $request->query('sort', '-id');

        $query = Article::query()->with('image');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('title_en', 'like', "%{$q}%")
                    ->orWhere('title_ar', 'like', "%{$q}%")
                    ->orWhere('brief_description_en', 'like', "%{$q}%")
                    ->orWhere('brief_description_ar', 'like', "%{$q}%");
            });
        }

        if (!is_null($isActive)) {
            $query->where('is_active', (bool) $isActive);
        }

        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column    = ltrim($sort, '-');
        if (!in_array($column, ['id','title_en','title_ar','created_at','updated_at'], true)) {
            $column = 'id';
        }
        $query->orderBy($column, $direction);

        $paginator = $query->paginate($perPage);

        return ApiResponse::success(
            ArticleResource::collection($paginator)->response()->getData(true),
            'قائمة المقالات'
        );
    }

    public function store(ArticleRequest $request, ArticleService $service): JsonResponse
    {
        $article = $service->create($request->validated());
        $article->load('image');

        return ApiResponse::success(
            new ArticleResource($article),
            'تم إنشاء المقالة بنجاح',
            201
        );
    }

    public function show(Article $article): JsonResponse
    {
        $article->load('image');
        return ApiResponse::success(
            new ArticleResource($article),
            'تفاصيل المقالة'
        );
    }

    public function update(ArticleUpdateRequest $request, Article $article, ArticleService $service): JsonResponse
    {
        $article = $service->update($article, $request->validated());
        $article->load('image');

        return ApiResponse::success(
            new ArticleResource($article),
            'تم تعديل المقالة بنجاح'
        );
    }

    public function destroy(Article $article, ArticleService $service): JsonResponse
    {
        $service->delete($article);

        return ApiResponse::success(null, 'تم حذف المقالة بنجاح');
    }

    public function toggle(Article $article): JsonResponse
    {
        $article->is_active = ! $article->is_active;
        $article->save();
        $article->load('image');

        return ApiResponse::success(
            new ArticleResource($article),
            'تم تغيير حالة التفعيل'
        );
    }
}
