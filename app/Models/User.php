<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasMediaTrait, Searchable, Notifiable;

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

    protected $appends = ['is_admin', 'is_chef', 'photo_url', 'is_impersonating', 'is_impersonated'];

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

    public function pendingOrders(): HasMany
    {
        return $this->orders()->pending();
    }

    public function createdOrder(Order $order): bool
    {
        return $order->owner_id === $this->id;
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 512, 512)
            ->nonQueued()
            ->performOnCollections('photo');
    }

    public function getPhotoAttribute()
    {
        if (!$this->hasMedia('photo')) {
            return null;
        }

        return (object) [
            'original' => $this->getFirstMedia('photo')->getFullUrl(),
            'thumb'    => $this->getFirstMedia('photo')->getFullUrl('thumb')
        ];
    }

    public function getPhotoUrlAttribute()
    {
        return optional($this->photo)->thumb;
    }

    public function getIsChefAttribute()
    {
        return $this->role == self::ROLE_CHEF;
    }

    public function getIsAdminAttribute()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function getIsImpersonatingAttribute()
    {
        return optional(impersonator())->id === $this->id;
    }

    public function getIsImpersonatedAttribute()
    {
        return !!impersonator();
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

    public function scopeWithPendingOrdersBetween(Builder $builder, array $range): Builder
    {
        return $this->whereHas('pendingOrders', function ($order) use ($range) {
            $order->betweenDates($range);
        });
    }

    public function routeNotificationForSlack($notification)
    {
        return config('slack.webhook');
    }
}
