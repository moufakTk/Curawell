<?php

namespace App\Services\Admin\Sections;

use App\Models\SmallService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SmallServiceService
{
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $q = SmallService::query()
            ->with(['smallService_section'])
            ->withCount(['divisions','whatPhotos']);

        if (!empty($filters['section_id'])) {
            $q->where('section_id', (int)$filters['section_id']);
        }

        if (!empty($filters['search'])) {
            $s = trim((string)$filters['search']);
            $q->where(fn($x) =>
            $x->where('name_en','like',"%$s%")
                ->orWhere('name_ar','like',"%$s%")
                ->orWhere('description_en','like',"%$s%")
                ->orWhere('description_ar','like',"%$s%")
            );
        }

        // sorting
        $sort = $filters['sort'] ?? 'id';
        $dir  = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sort, $dir);

        return $q->paginate($perPage);
    }

    public function create(array $data): SmallService
    {
        return DB::transaction(function() use ($data) {
            /** @var SmallService $ss */
            $ss = SmallService::create($data);
            return $ss->load(['smallService_section'])->loadCount(['divisions','whatPhotos']);
        });
    }

    public function update(SmallService $ss, array $data): SmallService
    {
        return DB::transaction(function() use ($ss, $data) {
            $ss->update($data);
            return $ss->load(['smallService_section'])->loadCount(['divisions','whatPhotos']);
        });
    }

    public function delete(SmallService $ss): void
    {
        DB::transaction(fn() => $ss->delete());
    }
}
