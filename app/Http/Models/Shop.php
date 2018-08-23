<?php

namespace App\Http\Models;

use Eloquent as Model;

class Shop extends Model
{
    protected $fillable = ['name', 'address', 'contact', 'flag'];

    protected $table = 'shops';
}
