<?php

namespace App\Http\Models;

use Eloquent as Model;

class Member extends Model
{
    protected $table = 'members';

    public function shop()
    {
        return $this->belongsTo('App\Http\Models\Shop');
    }

    public function formula()
    {
        return $this->hasOne('App\Http\Models\Formula');
    }
}
