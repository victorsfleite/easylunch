<?php

namespace App\Http\Requests\Options;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return user()->is_chef || user()->is_admin;
    }

    public function rules()
    {
        return [
            'name'  => 'required|unique:options',
            'price' => 'required|numeric|min:0',
        ];
    }
}
