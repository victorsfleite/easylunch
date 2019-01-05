<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Menu extends Model implements HasMedia
{
    use Searchable, HasMediaTrait;

    protected $fillable = [
        'date',
        'description',
    ];

    protected $searchableFields = [
        'description',
        'date'
    ];

    protected $with = ['orders', 'options'];

    protected $casts = [
        'date'       => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['image', 'income', 'income_preview'];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'menu_options')->withTimestamps()->withPivot('price');
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->whereHas('orders', function ($order) {
            $order->completed();
        });
    }

    public function getImageAttribute()
    {
        return [
            'original' => optional($this->getFirstMedia('image'))->getFullUrl()
        ];
    }

    public function getIncomePreviewAttribute()
    {
        return $this->orders->sum('price');
    }

    public function getIncomeAttribute()
    {
        return $this->orders()->completed()->get()->sum('price');
    }

    public function syncOptions(array $options)
    {
        $options = collect($options)->mapWithKeys(function ($option) {
            $price = $option['pivot']['price'] ?? $option['price'];

            return [$option['id'] => compact('price')];
        });

        $this->options()->sync($options);
        $this->refresh();
    }
}
