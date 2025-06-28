<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable =[
        'code',
        'user_id',
        'type',
        'channel',
        'expires_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
