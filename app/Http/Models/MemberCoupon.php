<?php

namespace App\Http\Models;

use Eloquent as Model;

class MemberCoupon extends Model
{
    protected $table = 'member_coupons';

    protected $fillable = ['coupon_id', 'member_id'];

    public function coupon()
    {
        return $this->belongsTo('App\Http\Models\Coupon');
    }
}
