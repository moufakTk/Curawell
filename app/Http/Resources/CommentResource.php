<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
        return [

            'comment_id'=>$this->id,
            'comment' =>$this->comment,
            'status'=>$this->status,
            'patient' => new UserInfoResource(optional(optional($this->comment_patient)->patient_user)),


        ];
    }

    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale();
        return $this->{"{$key}_{$locale}"} ?? null;
    }

}
