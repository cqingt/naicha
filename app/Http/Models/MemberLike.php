<?php

namespace App\Http\Models;

use Eloquent as Model;

class MemberLike extends Model
{
    protected $table = 'member_likes';

    protected $fillable = ['formula_id', 'member_id'];

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Member');
    }

    public function formula()
    {
        return $this->belongsTo('App\Http\Models\Formula');
    }
}
