<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class MarkPaidRequest extends FormRequest
{
    public function authorize()
    {
        return user()->is_admin;
    }

    public function rules()
    {
        return [
            'paid' => 'required|boolean',
        ];
    }
}
