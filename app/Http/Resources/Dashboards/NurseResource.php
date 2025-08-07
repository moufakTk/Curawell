<?php

namespace App\Http\Resources\Dashboards;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NurseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array

    {
        return $this->resource->map(function ($days, $month) {
            return [
                'month' => $month,
                'days' => collect($days)->map(function ($day) {
                    return [
//                        'id' => $day['id'],
                        'date' => $day['history'],

                        'day' => $day['day_'.app()->getLocale()],
                        'day_sessions' => collect($day['work_employees'])->map(function ($work) {
                            return [
                                'day_id' => $work['id'],
                                'sessions' => collect($work['nurse_sessions'])->map(function ($session) {
                                    return [
                                        'id' => $session['id'],
                                        'time' => $session['time_in']->format('h:i A')
                                        ,
                                        'status' => $session['status'],
                                    ];
                                })
                            ];
                        })
                    ];
                })
            ];
        })->values()->all(); // احرص على reset للأرقام المفتاحية

    }


    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale()??'en';

        return $this->{"{$key}_{$locale}"} ?? null;
    }



}
