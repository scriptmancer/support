<?php

namespace Nazim\Support;

class Validation
{
    /**
     * Validate an email address.
     *
     * @param string $email
     * @return bool
     */
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate a URL.
     *
     * @param string $url
     * @return bool
     */
    public static function url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate an IP address.
     *
     * @param string $ip
     * @param int $flags Additional flags for validation (FILTER_FLAG_IPV4, FILTER_FLAG_IPV6)
     * @return bool
     */
    public static function ip(string $ip, int $flags = 0): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, $flags) !== false;
    }

    /**
     * Validate an IPv4 address.
     *
     * @param string $ip
     * @return bool
     */
    public static function ipv4(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Validate an IPv6 address.
     *
     * @param string $ip
     * @return bool
     */
    public static function ipv6(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Validate a MAC address.
     *
     * @param string $mac
     * @return bool
     */
    public static function mac(string $mac): bool
    {
        return filter_var($mac, FILTER_VALIDATE_MAC) !== false;
    }

    /**
     * Validate a domain name.
     *
     * @param string $domain
     * @return bool
     */
    public static function domain(string $domain): bool
    {
        // This regex allows internationalized domain names
        $pattern = '/^(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/i';
        return preg_match($pattern, $domain) === 1;
    }

    /**
     * Validate a date string.
     *
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function date(string $date, string $format = 'Y-m-d'): bool
    {
        $dateTime = \DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }

    /**
     * Validate a credit card number using the Luhn algorithm.
     *
     * @param string $number
     * @return bool
     */
    public static function creditCard(string $number): bool
    {
        // Remove spaces and dashes
        $number = preg_replace('/\D/', '', $number);
        
        // Check if the number contains only digits
        if (!ctype_digit($number)) {
            return false;
        }
        
        // Check length
        $length = strlen($number);
        if ($length < 13 || $length > 19) {
            return false;
        }
        
        // Luhn algorithm
        $sum = 0;
        $doubleUp = false;
        
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];
            
            if ($doubleUp) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
            $doubleUp = !$doubleUp;
        }
        
        return $sum % 10 === 0;
    }

    /**
     * Validate a UUID v4.
     *
     * @param string $uuid
     * @return bool
     */
    public static function uuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }

    /**
     * Validate a JSON string.
     *
     * @param string $json
     * @return bool
     */
    public static function json(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate a numeric value.
     *
     * @param mixed $value
     * @return bool
     */
    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    /**
     * Validate an integer value.
     *
     * @param mixed $value
     * @return bool
     */
    public static function integer(mixed $value): bool
    {
        if (is_int($value)) {
            return true;
        }

        return is_string($value) && preg_match('/^-?\d+$/', $value) === 1;
    }

    /**
     * Validate a float value.
     *
     * @param mixed $value
     * @return bool
     */
    public static function float(mixed $value): bool
    {
        if (is_float($value)) {
            return true;
        }

        return is_string($value) && preg_match('/^-?\d+\.\d+$/', $value) === 1;
    }

    /**
     * Validate an alphanumeric string.
     *
     * @param string $value
     * @return bool
     */
    public static function alphaNumeric(string $value): bool
    {
        return ctype_alnum($value);
    }

    /**
     * Validate an alphabetic string.
     *
     * @param string $value
     * @return bool
     */
    public static function alpha(string $value): bool
    {
        return ctype_alpha($value);
    }

    /**
     * Validate a hexadecimal string.
     *
     * @param string $value
     * @return bool
     */
    public static function hex(string $value): bool
    {
        return ctype_xdigit($value);
    }

    /**
     * Validate a base64 encoded string.
     *
     * @param string $value
     * @return bool
     */
    public static function base64(string $value): bool
    {
        // Check if the string is valid base64
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value)) {
            return false;
        }
        
        // Try to decode and check if it worked
        $decoded = base64_decode($value, true);
        return $decoded !== false;
    }

    /**
     * Validate a password strength.
     *
     * @param string $password
     * @param int $minLength
     * @param bool $requireUppercase
     * @param bool $requireLowercase
     * @param bool $requireNumbers
     * @param bool $requireSpecialChars
     * @return bool
     */
    public static function password(
        string $password,
        int $minLength = 8,
        bool $requireUppercase = true,
        bool $requireLowercase = true,
        bool $requireNumbers = true,
        bool $requireSpecialChars = true
    ): bool {
        // Check minimum length
        if (strlen($password) < $minLength) {
            return false;
        }
        
        // Check for uppercase letters
        if ($requireUppercase && !preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // Check for lowercase letters
        if ($requireLowercase && !preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // Check for numbers
        if ($requireNumbers && !preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // Check for special characters
        if ($requireSpecialChars && !preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }
        
        return true;
    }

    /**
     * Validate a Turkish Identification Number (T.C. Kimlik No).
     *
     * @param string $id
     * @return bool
     */
    public static function tcKimlik(string $id): bool
    {
        // Must be 11 digits
        if (!preg_match('/^[0-9]{11}$/', $id)) {
            return false;
        }
        
        // First digit cannot be 0
        if ($id[0] === '0') {
            return false;
        }
        
        // Algorithm for validating the number
        $digits = str_split($id);
        $a = 0;
        $b = 0;
        $c = $digits[10];
        
        for ($i = 0; $i < 9; $i += 2) {
            $a += (int) $digits[$i];
        }
        
        for ($i = 1; $i < 9; $i += 2) {
            $b += (int) $digits[$i];
        }
        
        $rule1 = (($a * 7) - $b) % 10 === (int) $digits[9];
        $rule2 = (($a + $b + (int) $digits[9]) % 10) === (int) $c;
        
        return $rule1 && $rule2;
    }

    /**
     * Validate a Turkish Tax Number (Vergi Kimlik No).
     *
     * @param string $taxNumber
     * @return bool
     */
    public static function vergiKimlik(string $taxNumber): bool
    {
        // Must be 10 digits
        if (!preg_match('/^[0-9]{10}$/', $taxNumber)) {
            return false;
        }
        
        $digits = str_split($taxNumber);
        $sum = 0;
        
        for ($i = 0; $i < 9; $i++) {
            $digit = (int) $digits[$i];
            $digitSum = ($digit + (9 - $i)) % 10;
            
            if ($digitSum === 0) {
                $digitSum = 10;
            }
            
            $digitSum = ($digitSum * (int) pow(2, (9 - $i))) % 9;
            
            if ($digitSum === 0 && $digit !== 0) {
                $digitSum = 9;
            }
            
            $sum += $digitSum;
        }
        
        $checksum = (10 - ($sum % 10)) % 10;
        
        return $checksum === (int) $digits[9];
    }

    /**
     * Validate a phone number. This is a simple validation that only checks 
     * if the string contains 10-15 digits and optional formatting.
     *
     * @param string $phone
     * @return bool
     */
    public static function phone(string $phone): bool
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) >= 10 && strlen($phone) <= 15;
    }

    /**
     * Validate a string is a HEX color.
     *
     * @param string $color
     * @return bool
     */
    public static function hexColor(string $color): bool
    {
        return preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $color) === 1;
    }

    /**
     * Validate a string is a valid RGB color.
     *
     * @param string $color
     * @return bool
     */
    public static function rgbColor(string $color): bool
    {
        $pattern = '/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/';
        
        if (preg_match($pattern, $color, $matches)) {
            for ($i = 1; $i <= 3; $i++) {
                if ($matches[$i] < 0 || $matches[$i] > 255) {
                    return false;
                }
            }
            return true;
        }
        
        return false;
    }

    /**
     * Validate a string against a regular expression.
     *
     * @param string $value
     * @param string $pattern
     * @return bool
     */
    public static function regex(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value) === 1;
    }
} 