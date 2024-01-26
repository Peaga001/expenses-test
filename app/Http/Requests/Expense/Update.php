<?php

namespace App\Http\Requests\Expense;

//Utils
use App\Utils\PriceUtils;

//Rules
use App\Rules\Expense\{
    CheckPositiveValue,
    CheckValidDate
};

//Miscellaneous
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'     => ['nullable', 'exists:users,id'],
            'date'        => ['nullable', 'date', new CheckValidDate],
            'value'       => ['nullable', 'string', new CheckPositiveValue],
            'description' => ['nullable', 'string', 'max:191']
        ];
    }

    public function passedValidation(): void
    {
        if($value = $this->input('value')){
            $this->merge([
                'value' => PriceUtils::formatValueToSave($value)
            ]);
        }
    }
}
