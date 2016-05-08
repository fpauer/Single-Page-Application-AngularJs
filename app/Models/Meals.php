<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $fillable = ['description','calories','user_id','eat_at'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
