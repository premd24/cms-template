<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Translation\PotentiallyTranslatedString;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! config('services.recaptcha.enabled', true)) {
            return;
        }

        if (empty($value)) {
            $fail('The reCAPTCHA verification token is required.');

            return;
        }

        $secret = config('services.recaptcha.secret');
        if (empty($secret)) {
            // Fallback to Google's universal test secret key (always returns success)
            $secret = '6LeIxAcTAAAAAGG-vFI1dfg35Lytq5DQ0_Eh4Vyk';
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! $response->json('success')) {
                $fail('The reCAPTCHA verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            $fail('Unable to verify reCAPTCHA due to a connection error.');
        }
    }
}
