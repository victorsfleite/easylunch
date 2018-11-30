<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!password_verify($this->current_password, $this->user->password)) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });
        return;
    }
}
