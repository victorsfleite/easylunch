<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use Searchable;

    protected $fillable = [
        'description',
        'owner_id',
        'menu_id',
        'completed_at',
        'paid_at',
    ];

    protected $searchableFields = [
        'description',
    ];

    protected $with = ['owner', 'options'];

    protected $dates = [
        'updated_at',
        'created_at',
        'completed_at',
        'paid_at',
    ];

    protected $appends = ['price'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'order_options')->withTimestamps()->withPivot('price');
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending(Builder $query)
    {
        return $query->whereNull('paid_at');
    }

    public function scopeOfUser(Builder $builder, $user)
    {
        $userId = is_object($user) ? $user->id : $user;

        return $builder->whereOwnerId($userId);
    }

    public function scopeBetweenDates(Builder $query, $dates): Builder
    {
        return $query->whereHas('menu', function ($menu) use ($dates) {
            $start = new Carbon($dates[0] ?? $dates['start'] ?? $dates);
            $end = new Carbon($dates[1] ?? $dates['end'] ?? today());
            $menu->where('date', '>=', $start->toDateString())
                ->where('date', '<=', $end->toDateString());
        });
    }

    public function syncOptions(? array $options): self
    {
        $optionsToSync = collect($options)->mapWithKeys(function ($option) {
            return [$option['id'] => ['price' => array_get($option, 'pivot.price')]];
        });

        $this->options()->sync($optionsToSync);
        $this->refresh();

        return $this;
    }

    public function getPriceAttribute()
    {
        $basePrice      = 10;
        $priceOfOptions = 0;
        if (!is_null($this->options)) {
            $priceOfOptions = $this->options->map->pivot->sum('price');
        }

        return $basePrice + $priceOfOptions;
    }
}
