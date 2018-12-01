<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::id() === $this->user->id;
    }

    public function rules()
    {
        return [
            'name'  => 'required|string',
            'email' => 'required|email',
        ];
    }
}
