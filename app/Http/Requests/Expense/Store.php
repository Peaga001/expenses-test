<?php

namespace App\Http\Requests\Expense;

//Utils
use App\Utils\{
    PriceUtils,
    DateUtils
};

//Miscellaneous
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'value' => PriceUtils::formatValueToSave($this->input('value')),
            'date'  => DateUtils::formatDateToSave($this->input('date'))
        ]);
    }
}
