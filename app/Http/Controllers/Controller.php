<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function addMediaIfExists(HasMedia $model, $field, $collection = 'default')
    {
        if (request()->has($field)) {
            $model->addMediaFromRequest($field)->toMediaCollection($collection);
        }
    }
}
