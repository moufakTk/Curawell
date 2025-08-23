<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceStoreRequest;
use App\Http\Requests\Admin\ServiceUpdateRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Models\Service;
use App\Services\Admin\Sections\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ServiceController extends Controller
{
    public function __construct(private ServiceService $svc) {}

    public function index(Request $request)
    {
        $items = $this->svc->list(
            filters: [
                'section_id' => $request->integer('section_id'),
                'search'     => $request->string('search'),
            ],
            perPage: $request->integer('per_page', 20)
        );
$items->load('image','video');
        return ApiResponse::success(ServiceResource::collection($items));
    }

    public function store(ServiceStoreRequest $request)
    {
        try {
            $item = $this->svc->create($request->validated());
            return ApiResponse::success(new ServiceResource($item->load('image','video')), 'تمت الإضافة بنجاح', 201);
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function show(Service $service)
    {
        $service->load(['service_section'])->loadCount('competences');
        return ApiResponse::success(new ServiceResource($service->load('image','video')));
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        try {
            $item = $this->svc->update($service, $request->validated());
            return ApiResponse::success(new ServiceResource($item->load('image','video')), 'تم التحديث بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(Service $service)
    {
        try {
            $this->svc->delete($service);
            return ApiResponse::success(null, 'تم الحذف بنجاح');
        } catch (Throwable $e) {
            return ApiResponse::error(null, $e->getMessage(), 500);
        }
    }

    public function uploadServiceImage(Request $req, Service $service)
    {
        $req->validate([
            'file' => ['required','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $path = $req->file('file')->store('services/images','public');

        // امسح الصورة القديمة إن وجدت
        if ($service->images()->where('type','image')->exists()) {
            $old = $service->images()->where('type','image')->first();
            Storage::disk('public')->delete($old->path_image);
            $old->delete();
        }

        $img = $service->images()->create([
            'path_image' => $path,
            'type'       => 'image',
        ]);

        return ApiResponse::success([
            'id'  => $img->id,
            'url' => $img->url,
        ], 'تم رفع صورة الخدمة بنجاح');
    }

    public function uploadServiceVideo(Request $req, Service $service)
    {
        $req->validate([
            'file' => ['required','file','mimetypes:video/mp4,video/webm,video/ogg','max:51200'],
        ]);

        $path = $req->file('file')->store('services/videos','public');

        // امسح الفيديو القديم إن وجد
        if ($service->images()->where('type','video')->exists()) {
            $old = $service->images()->where('type','video')->first();
            Storage::disk('public')->delete($old->path_image);
            $old->delete();
        }

        $vid = $service->images()->create([
            'path_image' => $path,
            'type'       => 'video',
        ]);

        return ApiResponse::success([
            'id'  => $vid->id,
            'url' => $vid->url,
        ], 'تم رفع الفيديو بنجاح');
    }

}
