<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description','slug'];

    public function realStates()
    {
         return $this->belongsToMany(realStates::class, 'real_state_categories');   
    }
}
