<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;
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

    protected $appends = ['image'];

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
}
