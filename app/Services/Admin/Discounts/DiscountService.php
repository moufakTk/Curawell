<?php

namespace App\Services\Admin\Discounts;

use App\Models\Division;
use App\Models\Doctor;
use App\Models\SmallService;

class DiscountService
{
    public function searchDoctors(?string $name)
    {
        $term = trim((string) $name);

        $doctors = Doctor::query()
            ->with(['doctor_user:id,first_name,last_name,user_type'])
            ->when($term !== '', function ($q) use ($term) {
                $q->whereHas('doctor_user', function ($u) use ($term) {
                    $u->where('first_name', 'LIKE', "%{$term}%")
                        ->orWhere('last_name',  'LIKE', "%{$term}%")
                        ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$term}%"]);
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return $doctors->map(function ($d) {
            $u = $d->doctor_user;
            return [
                'id'   => $d->id,
                'name' => $u->full_name,
                'type' => $u->user_type,
            ];
        });
    }

    public function getDoctorsServices( $request)
    {
        $doctorIds = array_values(array_unique(array_map('intval', (array) $request->input('doctor_ids', []))));
        if (empty($doctorIds)) {
            return [];
        }

        if (count($doctorIds) === 1) {
            $serviceIds = Division::query()
                ->where('doctor_id', $doctorIds[0])
                ->pluck('small_service_id')
                ->unique()
                ->values();
        } else {
            $serviceIds = Division::query()
                ->whereIn('doctor_id', $doctorIds)
                ->select('small_service_id')
                ->groupBy('small_service_id')
                ->havingRaw('COUNT(DISTINCT doctor_id) = ?', [count($doctorIds)])
                ->pluck('small_service_id');
        }

        if ($serviceIds->isEmpty()) {
            return [];
        }

        $services = SmallService::query()
            ->whereIn('id', $serviceIds)
            ->select('id', 'name_en', 'name_ar', 'price', 'section_id')
            ->orderBy('id', 'desc')
            ->get();

        return $services->map(function ($s) {
            return [
                'id'         => $s->id,
                'name_en'    => $s->name_en,
                'name_ar'    => $s->name_ar,
                'price'      => $s->price,
                'section_id' => $s->section_id,
            ];
        })->all();
    }
        // … منطق الإنشاء لاحقًا

}
