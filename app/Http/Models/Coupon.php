<?php

namespace App\Http\Models;

use Eloquent as Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = ['title', 'condition', 'shop_id', 'amount', 'start_time', 'stop_time', 'is_send', 'match_price', 'reduced_price'];

    public function shop()
    {
        return $this->belongsTo('App\Http\Models\Shop');
    }
}
