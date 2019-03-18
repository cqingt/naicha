<?php

namespace App\Http\Models;

use Eloquent as Model;

class MemberMessage extends Model
{
    protected $table = 'member_messages';

    protected $fillable = ['member_id'];

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Member');
    }
}
