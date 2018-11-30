<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use Searchable;

    protected $fillable = [
        'date',
        'description',
    ];

    protected $searchableFields = [
        'description',
    ];
}
