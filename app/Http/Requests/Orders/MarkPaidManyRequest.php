<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class MarkPaidManyRequest extends FormRequest
{
    public function authorize()
    {
        return user()->is_admin;
    }

    public function rules()
    {
        return [
            'ids'  => 'required|array',
            'paid' => 'required|boolean',
        ];
    }
}
