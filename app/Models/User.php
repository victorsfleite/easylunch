<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_CHEF  = 'chef';
    const ROLE_USER  = 'user';

    const ROLES      = [
        self::ROLE_ADMIN,
        self::ROLE_CHEF,
        self::ROLE_USER,
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['is_admin', 'is_chef'];

    public function setPasswordAttribute($password)
    {
        if ($password && !is_bool($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'owner_id');
    }

    public function createdOrder(Order $order): bool
    {
        return $order->owner_id === $this->id;
    }

    public function getIsChefAttribute()
    {
        return $this->role == self::ROLE_CHEF;
    }

    public function getIsAdminAttribute()
    {
        return $this->role == self::ROLE_ADMIN;
    }
}
