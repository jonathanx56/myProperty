<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function state()
    {
        return $this->belongsTo(state::class);
    }

    public function city()
    {
        return $this->belongsTo(city::class);
    }
}
