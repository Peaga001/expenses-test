<?php

namespace App\Rules\Expense;

use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class CheckPositiveValue implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if((float) $value < 0.01){
            $fail('Valor não pode ser negativo!');
        }
    }
}
