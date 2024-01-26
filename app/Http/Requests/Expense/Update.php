<?php

namespace App\Http\Requests\Expense;

//Utils
use App\Utils\{
    PriceUtils,
    DateUtils
};

//Miscellaneous
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function prepareForValidation(): void
    {
        if($value = $this->input('value')){
            $this->merge([
                'value' => PriceUtils::formatValueToSave($value),
            ]);
        }

        if($date = $this->input('date')){
            $this->merge([
                'date'  => DateUtils::formatDateToSave($date)
            ]);
        }
    }
}
