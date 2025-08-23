<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// App/Models/Image.php
class Image extends Model
{
    protected $fillable = ['path_image','type']; // لا تضيف imageable للـfillable

    public function imageable()
    {
        return $this->morphTo();
    }

    // رجّع URL جاهز للعرض
    protected $appends = ['url'];
    public function getUrlAttribute(): ?string
    {
        return $this->path_image
            ? Storage::disk('public')->url($this->path_image)
            : null;
    }
}
