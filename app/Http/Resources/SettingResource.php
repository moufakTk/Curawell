<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;




class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $locale;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
    }


    public function toArray(Request $request): array
    {
        $routeName = $request->route()->getName();

        return match ($routeName) {
            'settings.Info' => [
                'site_name' => $this->site_name,
                'preface_'.$this->locale => $this->getLocalized("preface"),
                'wise_'.$this->locale=>$this->getLocalized("wise"),
            ],

            'settings.contactUs' => [
                'email' => $this->email,
                'inquiry_number'=>$this->inquiry_number,
                'complaint_number'=>$this->complaint_number,
                'address'=>$this->address_en,
                'phone' => $this->phone,

            ],

            default => [
                // fallback أو كل الحقول
                'site_name' => $this->getLocalized('site_name'),
                'email'     => $this->email,
                'phone'     => $this->phone,
                'logo'      => $this->logo,
            ],
        };

    }

    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale();
        return $this->{"{$key}_{$locale}"} ?? null;
    }

}
