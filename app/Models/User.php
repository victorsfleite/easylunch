<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

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

    public function isChef()
    {
        return true;
    }
}
