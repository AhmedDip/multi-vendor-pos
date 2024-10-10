<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Phone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    final public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match("/^01\d*$/", $value)) {
            $fail('The :attribute must be a valid phone number.');
        }
        if (strlen($value) !== 11) {
            $fail('The :attribute must be 11 digit.');
        }
    }
}
