<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionStoreRequest;
use App\Http\Requests\Admin\SectionUpdateRequest;
use App\Http\Resources\Admin\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $q = Section::query();

        // فلترة بالبحث
        if ($search = trim((string)$request->query('search', ''))) {
            $q->where(function($x) use ($search) {
                $x->where('name_en','like',"%{$search}%")
                    ->orWhere('name_ar','like',"%{$search}%")
                    ->orWhere('brief_description_en','like',"%{$search}%")
                    ->orWhere('brief_description_ar','like',"%{$search}%");
            });
        }

        // فلترة بحسب نوع القسم (int من Enum)
        if ($request->filled('section_type')) {
            $q->where('section_type', (int)$request->query('section_type'));
        }

        // عدّادات اختيارية
        if ($request->boolean('with_counts', true)) {
            $q->withCount(['services','small_services']);
        }

        // ترتيب بسيط (id desc افتراضياً)
        $sort = $request->query('sort', 'id');
        $dir  = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sort, $dir);

        $items = $q->with('image')->paginate($request->integer('per_page', 20));

        return ApiResponse::success(SectionResource::collection($items));
    }

    public function store(SectionStoreRequest $request)
    {
        try {
            $section = Section::create($request->validated());
            $section->load('image')->loadCount(['services','small_services']);
            return ApiResponse::success(new SectionResource($section), 'تمت الإضافة بنجاح', 201);
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function show(Section $section)
    {
        $section->load('image')->loadCount(['services','small_services']);
        return ApiResponse::success(new SectionResource($section));
    }

    public function update(SectionUpdateRequest $request, Section $section)
    {
        try {
            $section->update($request->validated());
            $section->loadCount(['services','small_services']);
            return ApiResponse::success(new SectionResource($section), 'تم التحديث بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(Section $section)
    {
        try {
            $section->delete();
            return ApiResponse::success(null, 'تم الحذف بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }
    public function uploadSectionImage(Request $req, Section $section)
    {
        $req->validate([
            'file' => ['required','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $path = $req->file('file')->store('sections','public');

        // إذا في صورة قديمة → امسحها من disk والسجل
        if ($section->images()->where('type','image')->exists()) {
            $old = $section->images()->where('type','image')->first();
            Storage::disk('public')->delete($old->path_image);
            $old->delete();
        }

        // أنشئ الصورة الجديدة
        $img = $section->images()->create([
            'path_image' => $path,
            'type'       => 'image',
        ]);

        return ApiResponse::success([
            'id'  => $img->id,
            'url' => $img->url,
        ], 'تم رفع الصورة بنجاح');
    }

}
