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

    public function currentUser()
    {
        return auth()->user();
    }

    protected function addMediaIfExists(HasMedia $model, $field, $collection = 'default')
    {
        if (request()->has($field)) {
            $model->addMediaFromRequest($field)->toMediaCollection($collection);
        }
    }
}
