<?php

namespace App\Models;

use App\Traits\Models\Searchable;
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
    ];

    protected $appends = ['image', 'income'];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('image')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        return [
            'original' => optional($this->getFirstMedia('image'))->getFullUrl()
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getIncomeAttribute()
    {
        $ordersCount = $this->orders()->count();

        $pricePerUnit = 12;

        if ($ordersCount >= 5) {
            $pricePerUnit = 10;
        } elseif ($ordersCount >= 3) {
            $pricePerUnit = 11;
        }

        return $pricePerUnit * $ordersCount;
    }
}
