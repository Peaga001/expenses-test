<?php

namespace app\Rules\Expense;

use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;
use Closure;

class CheckValidDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentDate = Carbon::now('America/Sao_Paulo');
        $expenseDate = Carbon::make($value);

        if($expenseDate->greaterThan($currentDate->toDateString())){
            $fail('Data da despesa não pode ser maior do que a data atual!');
        }
    }
}
