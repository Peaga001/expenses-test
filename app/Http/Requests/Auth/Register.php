<?php

namespace App\Http\Requests\Auth;

//Miscellaneous
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Register extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string'],
            'email'    => ['required', 'string', 'unique:users'],
            'password' => ['required', 'min:6']
        ];
    }

    public function passedValidation(): void
    {
        $password      = Hash::make($this->input('password'));
        $rememberToken = Str::random(10);

        $this->merge([
            'password'       => $password,
            'remember_token' => $rememberToken
        ]);
    }
}
