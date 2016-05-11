<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $table = 'meals';
    protected $fillable = ['description','calories','user_id','consumed_at'];
    protected $visible = ['id', 'description', 'calories', 'user_id', 'consumed_at', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
