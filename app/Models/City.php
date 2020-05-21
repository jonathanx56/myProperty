<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function state()
    {
        return $this->belongsTo(state::class);
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }
}
