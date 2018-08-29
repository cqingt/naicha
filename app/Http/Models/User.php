<?php

namespace App\Http\Models;

//use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Eloquent as Model;

class User extends Model
{
    protected $table = 'users';
    use HasRoles;
    use Authenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        if(strlen($value)!=60)
        {
            $value=bcrypt($value);
        }
        $this->attributes['password']=$value;
    }

    public function role()
    {
        return $this->belongsTo('App\Http\Models\Role');
    }
}
