<?php

namespace App\Http\Models;

use Eloquent as Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name', 'description'
    ];
}
