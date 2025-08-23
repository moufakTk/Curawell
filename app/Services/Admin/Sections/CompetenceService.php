<?php

namespace App\Services\Admin\Sections;

use App\Models\Competence;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CompetenceService
{
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $q = Competence::query()->with(['competence_services.service_section']);

        if (!empty($filters['service_id'])) {
            $q->where('service_id', (int) $filters['service_id']);
        }
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(fn($x) => $x->where('name_en','like',"%$s%")
                ->orWhere('name_ar','like',"%$s%"));
        }

        return $q->orderByDesc('id')->paginate($perPage);
    }

    public function create(array $data): Competence
    {
        return DB::transaction(function() use ($data){
            /** @var Competence $comp */
            $comp = Competence::create([
                'service_id' => $data['service_id'],
                'name_en'    => $data['name_en'],
                'name_ar'    => $data['name_ar'],
                'brief_description_en' => $data['brief_description_en'] ?? [],
                'brief_description_ar' => $data['brief_description_ar'] ?? [],
            ]);

            return $comp->load(['competence_services.service_section']);
        });
    }

    public function update(Competence $competence, array $data): Competence
    {
        return DB::transaction(function() use ($competence, $data){
            $competence->fill($data);
            if (!array_key_exists('brief_description_en',$data) && $competence->brief_description_en === null) {
                $competence->brief_description_en = [];
            }
            if (!array_key_exists('brief_description_ar',$data) && $competence->brief_description_ar === null) {
                $competence->brief_description_ar = [];
            }
            $competence->save();

            return $competence->load(['competence_services.service_section']);
        });
    }

    public function delete(Competence $competence): void
    {
        DB::transaction(function() use ($competence){
            $competence->delete();
        });
    }
}
