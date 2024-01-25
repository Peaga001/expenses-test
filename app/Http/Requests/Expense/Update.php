<?php

namespace App\Http\Requests\Expense;

//Rules
use App\Rules\Expense\{
    CheckPositiveValue,
    CheckValidDate
};

//Miscellaneous
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date', new CheckValidDate],
            'value' => ['required', 'string', new CheckPositiveValue],
            'description' => ['required', 'string', 'max:191']
        ];
    }
}
