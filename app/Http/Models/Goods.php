<?php

namespace App\Http\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'goods';

    public function category()
    {
        return $this->belongsTo('App\Http\Models\Category');
    }
}
