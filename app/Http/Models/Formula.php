<?php

namespace App\Http\Models;

use Eloquent as Model;

class Formula extends Model
{
    protected $table = 'formulas';

    public function shop()
    {
        return $this->belongsTo('App\Http\Models\Shop');
    }

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Member');
    }

    public function order()
    {
        return $this->belongsTo('App\Http\Models\Order');
    }
}
