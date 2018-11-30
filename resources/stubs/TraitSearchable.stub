<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(Builder $query, $search)
    {
        if (is_null($search)) {
            return $query;
        }

        $fields = collect($this->searchableFields);
        if ($fields->isEmpty()) {
            return $query;
        }

        $firstField = $fields->shift();
        $query->whereRaw("LOWER(`{$firstField}`) LIKE ?", '%' . strtolower($search) . '%');

        foreach ($fields as $field) {
            $query->orWhereRaw("LOWER(`{$field}`) LIKE ?", '%' . strtolower($search) . '%');
        }

        return $query;
    }
}
