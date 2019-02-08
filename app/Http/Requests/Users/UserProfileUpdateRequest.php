<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::id() === $this->user->id;
    }

    public function rules()
    {
        return [
            'new_photo' => 'sometimes|file|mimes:jpeg,jpg,png',
            'name'      => 'required|string',
            'email'     => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'new_photo.mimes' => 'A foto deve ser um arquivo do tipo: jpeg, jpg, png.'
        ];
    }
}
