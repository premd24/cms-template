<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class MinHtmlTextRule implements ValidationRule
{
    protected int $min;

    protected string $fieldName;

    /**
     * Create a new rule instance.
     */
    public function __construct(int $min = 5, string $fieldName = 'content')
    {
        $this->min = $min;
        $this->fieldName = $fieldName;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Strip HTML tags and decode entities to check true plain-text character length
        $plainText = strip_tags(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
        $plainText = trim(str_replace('&nbsp;', ' ', $plainText));

        if (mb_strlen($plainText) < $this->min) {
            $fail("The {$this->fieldName} must be at least {$this->min} characters long.");
        }
    }
}
