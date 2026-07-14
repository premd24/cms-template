<?php

namespace App\Helpers;

class TotpHelper
{
    private static $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Decode a base32 string.
     */
    public static function base32Decode(string $base32): string
    {
        $base32 = strtoupper($base32);
        $base32 = preg_replace('/[^A-Z2-7]/', '', $base32);

        $buffer = 0;
        $bufferLength = 0;
        $decoded = '';

        for ($i = 0, $len = strlen($base32); $i < $len; $i++) {
            $char = $base32[$i];
            $val = strpos(self::$chars, $char);

            $buffer = ($buffer << 5) | $val;
            $bufferLength += 5;

            if ($bufferLength >= 8) {
                $bufferLength -= 8;
                $decoded .= chr(($buffer >> $bufferLength) & 0xFF);
            }
        }

        return $decoded;
    }

    /**
     * Encode binary string to base32.
     */
    public static function base32Encode(string $data): string
    {
        $encoded = '';
        $buffer = 0;
        $bufferLength = 0;

        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $buffer = ($buffer << 8) | ord($data[$i]);
            $bufferLength += 8;

            while ($bufferLength >= 5) {
                $bufferLength -= 5;
                $encoded .= self::$chars[($buffer >> $bufferLength) & 0x1F];
            }
        }

        if ($bufferLength > 0) {
            $buffer = $buffer << (5 - $bufferLength);
            $encoded .= self::$chars[$buffer & 0x1F];
        }

        return $encoded;
    }

    /**
     * Generate a random base32 secret.
     */
    public static function generateSecret(int $length = 16): string
    {
        $randomBytes = random_bytes($length);
        $secret = self::base32Encode($randomBytes);

        return substr($secret, 0, $length);
    }

    /**
     * Get the TOTP code for a given secret at a time slice.
     */
    public static function getCode(string $secret, ?int $timeSlice = null): string
    {
        if ($timeSlice === null) {
            $timeSlice = (int) floor(time() / 30);
        }

        $secretKey = self::base32Decode($secret);

        // Pack time into 8-byte binary string
        $timeBytes = pack('N*', 0).pack('N*', $timeSlice);

        // HMAC-SHA1
        $hash = hash_hmac('sha1', $timeBytes, $secretKey, true);

        // Dynamic truncation
        $offset = ord($hash[19]) & 0x0F;
        $truncatedHash = (
            (ord($hash[$offset]) & 0x7F) << 24 |
            (ord($hash[$offset + 1]) & 0xFF) << 16 |
            (ord($hash[$offset + 2]) & 0xFF) << 8 |
            (ord($hash[$offset + 3]) & 0xFF)
        );

        $code = $truncatedHash % 1000000;

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a TOTP code against a secret.
     */
    public static function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $currentTimeSlice = (int) floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = self::getCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate otpauth URL and QR code API URL.
     */
    public static function getQrCodeUrl(string $label, string $secret, string $issuer): string
    {
        $url = 'otpauth://totp/'.rawurlencode($issuer.':'.$label).'?secret='.$secret.'&issuer='.rawurlencode($issuer);

        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.urlencode($url);
    }

    /**
     * Generate backup recovery codes.
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = sprintf(
                '%04x-%04x',
                random_int(0, 0xFFFF),
                random_int(0, 0xFFFF)
            );
        }

        return $codes;
    }
}
