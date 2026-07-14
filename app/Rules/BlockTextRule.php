<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BlockTextRule implements ValidationRule
{
    /**
     * All block data from the request to look up types.
     *
     * @var array
     */
    protected $blocks;

    /**
     * Create a new rule instance.
     */
    public function __construct(array $blocks = [])
    {
        $this->blocks = $blocks;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extract block index to determine type
        preg_match('/blocks\.(\d+)\.content\.text/', $attribute, $matches);
        if (isset($matches[1])) {
            $index = $matches[1];
            $block = $this->blocks[$index] ?? [];
            $type = $block['type'] ?? '';

            if (in_array($type, ['heading', 'sub_heading', 'paragraph', 'quote'])) {
                // Strip HTML tags and decode entities to check true plain text character length
                $plainText = trim(html_entity_decode(strip_tags($value), ENT_QUOTES, 'UTF-8'));

                if (mb_strlen($plainText) > 0 && mb_strlen($plainText) < 3) {
                    $fail('This block\'s content must be at least 3 characters long.');
                }

                $maxLimit = $type === 'paragraph' ? 20000 : 255;
                if (mb_strlen($plainText) > $maxLimit) {
                    $fail("This block's content cannot exceed {$maxLimit} characters.");
                }
            }
        }
    }
}
