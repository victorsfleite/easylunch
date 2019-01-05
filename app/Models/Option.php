<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $searchableFields = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_options')->withTimestamps()->withPivot('price');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_options')->withTimestamps()->withPivot('price');
    }
}
