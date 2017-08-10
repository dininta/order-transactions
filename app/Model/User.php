<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User Group
     */
    const CUSTOMER = 0;
    const ADMIN = 1;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->group_type === static::ADMIN;
    }
}
