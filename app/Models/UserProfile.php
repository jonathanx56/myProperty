<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['phone', 'mobile_phone', 'about', 'social_networks'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
