<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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

    protected $with = ['orders'];

    protected $appends = ['image', 'income', 'income_preview'];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('image')
            ->singleFile();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
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
        return $this->calculateIncome($this->orders()->count());
    }

    public function getIncomeAttribute()
    {
        return $this->calculateIncome($this->orders()->completed()->count());
    }

    public function calculateIncome(int $ordersCount)
    {
        $pricePerUnit = 12;

        if ($ordersCount >= 5) {
            $pricePerUnit = 10;
        } elseif ($ordersCount >= 3) {
            $pricePerUnit = 11;
        }

        return $pricePerUnit * $ordersCount;
    }
}
