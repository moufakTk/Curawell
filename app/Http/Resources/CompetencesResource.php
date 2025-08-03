<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetencesResource extends JsonResource
{

    protected $locale;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return match ($request->route()->getName()) {

            'doctor.services'=>[
                'service_id'=>$this->service_id,
                'competences'=>[
                    'competence_id' => $this->id,
                    'competence_name_' . $this->locale => $this->getLocalized('name'),
                    'competence_doctors' => $this->work_locations?->map(function ($location) {
                        return new UserInfoResource(optional($location->work_location_user)?->doctor);
                    }),
                ]
            ],
            'competences.service'=>[

                'competence_name_'.$this->locale => $this->getLocalized('name'),
                'competence_description_'.$this->locale => $this->getLocalizedJson('brief_description'),


            ],


        };
    }


    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale();
        return $this->{"{$key}_{$locale}"} ?? null;
    }

    protected function getLocalizedJson(string $key): mixed
    {
        $locale = app()->getLocale();
        $fieldName = "{$key}_{$locale}";

        // تأكد إذا الحقل موجود ومو null
        if (!isset($this->$fieldName)) {
            return null;
        }

        // رجّع القيمة مفكوكه كـ array
        return is_string($this->$fieldName)
            ? json_decode($this->$fieldName, true)
            : $this->$fieldName; // إذا أصلاً متحوّلة
    }


}
