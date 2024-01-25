<?php

namespace App\Http\Requests\Auth;

//Miscellaneous
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'min:6']
        ];
    }
}
