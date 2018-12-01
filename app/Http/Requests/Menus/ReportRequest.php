<?php

namespace App\Http\Requests\Menus;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    public function rules()
    {
        return [
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ];
    }
}
