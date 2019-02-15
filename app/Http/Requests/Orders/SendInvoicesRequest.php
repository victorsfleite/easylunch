<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class SendInvoicesRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'start' => 'required|date',
            'end'   => 'required|date|after:start',
        ];
    }
}
