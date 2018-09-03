<?php

namespace App\Http\Models;

use Eloquent as Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['status'];


    public function member()
    {
        return $this->belongsTo('App\Http\Models\Member');
    }

    public function shop()
    {
        return $this->belongsTo('App\Http\Models\Shop');
    }

    public function details()
    {
        return $this->hasMany('App\Http\Models\OrderDetail');
    }
}
