<?php

namespace App\Services\Admin\Sections;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ServiceService
{
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $q = Service::query()->with(['service_section'])->withCount('competences');

        if (!empty($filters['section_id'])) {
            $q->where('section_id', (int) $filters['section_id']);
        }
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(fn($x) => $x->where('name_en','like',"%$s%")
                ->orWhere('name_ar','like',"%$s%"));
        }

        return $q->orderByDesc('id')->paginate($perPage);
    }

    public function create(array $data): Service
    {
        return DB::transaction(function() use ($data){
            /** @var Service $service */
            $service = Service::create([
                'section_id' => $data['section_id'],
                'name_en'    => $data['name_en'],
                'name_ar'    => $data['name_ar'],
                'details_services_en' => $data['details_services_en'] ?? [],
                'details_services_ar' => $data['details_services_ar'] ?? [],
            ]);

            return $service->load(['service_section'])->loadCount('competences');
        });
    }

    public function update(Service $service, array $data): Service
    {
        return DB::transaction(function() use ($service, $data){
            $service->fill($data);
            if (!array_key_exists('details_services_en',$data) && $service->details_services_en === null) {
                $service->details_services_en = [];
            }
            if (!array_key_exists('details_services_ar',$data) && $service->details_services_ar === null) {
                $service->details_services_ar = [];
            }
            $service->save();

            return $service->load(['service_section'])->loadCount('competences');
        });
    }

    public function delete(Service $service): void
    {
        DB::transaction(function() use ($service){
            // لو في علاقات لازم تنحذف/تتفك قبل.. الآن بس حذف
            $service->delete();
        });
    }
}
