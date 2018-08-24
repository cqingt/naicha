<?php

namespace App\Http\Models;

use Eloquent as Model;

class Push extends Model
{
    protected $table = 'pushes';

    protected $fillable = ['title', 'image', 'shop_id', 'position', 'position'];

    public function shop()
    {
        return $this->belongsTo('App\Http\Models\Shop');
    }
}
