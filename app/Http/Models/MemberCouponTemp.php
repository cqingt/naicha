<?php

namespace App\Http\Models;

use Eloquent as Model;

class MemberCouponTemp extends Model
{
    protected $table = 'member_coupons_temp';

    protected $fillable = ['coupon_id', 'phone'];
}
