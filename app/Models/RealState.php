<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{

    protected $table = 'real_states';
    protected $fillable = [
        'user_id', 'title', 'description', 'content',
        'price', 'slug', 'bedrooms', 'bathrooms', 'property_area',
        'total_property_area'
    ];

    public function user()
    {
        return $this->belongTo(user::class);
    }
}
