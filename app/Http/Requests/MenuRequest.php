<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_chef || $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'date'                  => 'required',
            'description'           => 'required',
            'options'               => 'sometimes|required|array',
            'options.*.pivot.price' => 'required_with:options|numeric'
        ];
    }
}
