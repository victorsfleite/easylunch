<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Searchable, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_CHEF  = 'chef';
    const ROLE_USER  = 'user';

    const ROLES      = [
        self::ROLE_ADMIN,
        self::ROLE_CHEF,
        self::ROLE_USER,
    ];

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $searchableFields = [
        'name',
        'email',
    ];

    protected $appends = ['is_admin', 'is_chef'];

    public function setPasswordAttribute($password)
    {
        if ($password && !is_bool($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'owner_id');
    }

    public function ordersCompleted(): HasMany
    {
        return $this->orders()->completed();
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

    public function totalAmountInRange(array $range)
    {
        return 10 * $this->ordersCompleted()->betweenDates($range)->count();
    }

    public function scopeWhereHasOrdersBetweenDates(Builder $builder, array $range): Builder
    {
        return $this->whereHas('ordersCompleted', function ($order) use ($range) {
            $order->betweenDates($range);
        });
    }

    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }
}
