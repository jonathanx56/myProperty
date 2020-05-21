<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['state_id', 'city_id'];

    public function state()
    {
        return $this->belongsTo(state::class);
    }

    public function city()
    {
        return $this->belongsTo(city::class);
    }
    public function real_state()
    {
        return $this->hasOne(RealState::class);
    }
}
