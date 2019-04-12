<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description'           => 'required',
            'options'               => 'sometimes|nullable|array',
            'options.*.pivot.price' => 'sometimes|numeric|min:0',
            'selected_user'         => 'sometimes|numeric|min:1',
        ];
    }
}
