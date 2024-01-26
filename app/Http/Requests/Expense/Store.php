<?php

namespace App\Http\Requests\Expense;

//Utils
use App\Utils\{
    PriceUtils,
    DateUtils
};

//Rules
use App\Rules\Expense\{
    CheckPositiveValue,
    CheckValidDate
};

//Miscellaneous
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'     => ['required', 'exists:users,id'],
            'date'        => ['required', 'date', new CheckValidDate],
            'value'       => ['required', 'string', new CheckPositiveValue],
            'description' => ['required', 'string', 'max:191']
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'value' => PriceUtils::formatValueToSave($this->input('value')),
            'date'  => DateUtils::formatDateToSave($this->input('date'))
        ]);
    }
}
