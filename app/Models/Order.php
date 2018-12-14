<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Searchable;

    protected $fillable = [
        'description',
        'owner_id',
        'menu_id',
        'completed_at',
    ];

    protected $searchableFields = [
        'description',
    ];

    protected $with = ['owner'];

    protected $dates = [
        'updated_at',
        'created_at',
        'completed_at',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeBetweenDates(Builder $query, $dates): Builder
    {
        return $query->whereHas('menu', function ($menu) use ($dates) {
            $menu->where('date', '>=', $dates[0] ?? $dates['start'] ?? $dates)
                ->where('date', '<=', $dates[1] ?? $dates['end'] ?? today());
        });
    }
}
