<?php

namespace App\Http\Requests\Options;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return user()->is_chef || user()->is_admin;
    }

    public function rules()
    {
        return [
            'name'  => 'sometimes|required|unique:options',
            'price' => 'sometimes|required|numeric|min:0',
        ];
    }
}
