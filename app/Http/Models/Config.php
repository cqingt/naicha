<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'name','keywords','description','email','url','status','statistics'
        ];
}
