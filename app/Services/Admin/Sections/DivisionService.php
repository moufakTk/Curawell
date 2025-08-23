<?php

namespace App\Services\Admin\Sections;

use App\Models\Division;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DivisionService
{
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $q = Division::query()
            ->with(['division_small_service.smallService_section','division_doctor'])
            ->withCount('treatments');

        if (!empty($filters['doctor_id'])) {
            $q->where('doctor_id', (int)$filters['doctor_id']);
        }
        if (!empty($filters['small_service_id'])) {
            $q->where('small_service_id', (int)$filters['small_service_id']);
        }
        if (!empty($filters['section_id'])) {
            // فلترة عبر join على small_services → section_id
            $q->whereHas('division_small_service', function($qq) use ($filters) {
                $qq->where('section_id', (int)$filters['section_id']);
            });
        }
        if (isset($filters['is_discounted'])) {
            $q->where('is_discounted', (bool)$filters['is_discounted']);
        }

        // sorting
        $sort = $filters['sort'] ?? 'id';
        $dir  = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sort, $dir);

        return $q->paginate($perPage);
    }

    public function create(array $data): Division
    {
        return DB::transaction(function() use ($data) {
            /** @var Division $d */
            $d = Division::create([
                'small_service_id' => $data['small_service_id'],
                'doctor_id'        => $data['doctor_id'],
                'is_discounted'    => (bool)($data['is_discounted'] ?? false),
                'discount_rate'    => (float)($data['discount_rate'] ?? 0),
            ]);

            return $d->load(['division_small_service.smallService_section','division_doctor'])
                ->loadCount('treatments');
        });
    }

    public function update(Division $division, array $data): Division
    {
        return DB::transaction(function() use ($division, $data) {
            $division->update($data);

            return $division->load(['division_small_service.smallService_section','division_doctor'])
                ->loadCount('treatments');
        });
    }

    public function delete(Division $division): void
    {
        DB::transaction(fn() => $division->delete());
    }

    public function toggleDiscount(Division $division, bool $isDiscounted, float $rate = 0): Division
    {
        return DB::transaction(function() use ($division, $isDiscounted, $rate) {
            $division->is_discounted = $isDiscounted;
            $division->discount_rate = $isDiscounted ? max(0, min(100, $rate)) : 0;
            $division->save();

            return $division->refresh();
        });
    }
}
