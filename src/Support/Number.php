<?php

namespace Nazim\Support;

class Number
{
    /**
     * The locale to use for number formatting.
     *
     * @var string
     */
    protected $locale;

    /**
     * Create a new Number instance.
     *
     * @param string $locale
     */
    public function __construct(string $locale = 'tr_TR')
    {
        $this->locale = $locale;
    }

    /**
     * Format a number according to the current locale.
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public function format(float $number, int $decimals = 2): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);
            return $formatter->format($number);
        }
        
        // Fallback if Intl extension is not available
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Format a number as currency according to the current locale.
     *
     * @param float $number
     * @param string|null $currency Currency code (e.g., TRY, USD, EUR)
     * @return string
     */
    public function currency(float $number, ?string $currency = null): string
    {
        if (class_exists('NumberFormatter')) {
            // Determine default currency based on locale if not provided
            if ($currency === null) {
                if ($this->locale === 'tr_TR') {
                    $currency = 'TRY';
                } elseif (strpos($this->locale, 'en_US') === 0) {
                    $currency = 'USD';
                } elseif (strpos($this->locale, 'en_GB') === 0) {
                    $currency = 'GBP';
                } elseif (strpos($this->locale, 'de') === 0) {
                    $currency = 'EUR';
                } else {
                    $currency = 'USD'; // Default fallback
                }
            }
            
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($number, $currency);
        }
        
        // Fallback if Intl extension is not available
        return number_format($number, 2, ',', '.') . ' ' . ($currency ?? '');
    }

    /**
     * Format a number as percentage according to the current locale.
     *
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public function percent(float $number, int $decimals = 1): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::PERCENT);
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);
            return $formatter->format($number);
        }
        
        // Fallback if Intl extension is not available
        return number_format($number * 100, $decimals, ',', '.') . '%';
    }

    /**
     * Format a number in a human-readable way (e.g., 1K, 1M, 1B).
     *
     * @param float $number
     * @return string
     */
    public function shortForm(float $number): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 1);
            
            if ($number >= 1000000000) {
                return $formatter->format($number / 1000000000) . 'B';
            } elseif ($number >= 1000000) {
                return $formatter->format($number / 1000000) . 'M';
            } elseif ($number >= 1000) {
                return $formatter->format($number / 1000) . 'K';
            }
            
            return $formatter->format($number);
        }
        
        // Fallback if Intl extension is not available
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 1, ',', '.') . 'B';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 1, ',', '.') . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1, ',', '.') . 'K';
        }
        
        return number_format($number, 0, ',', '.');
    }

    /**
     * Format a number using words (e.g., "one thousand two hundred").
     *
     * @param float $number
     * @return string
     */
    public function spellOut(float $number): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::SPELLOUT);
            return $formatter->format($number);
        }
        
        // No good fallback for spell out, return formatted number instead
        return number_format($number, 0, ',', '.');
    }

    /**
     * Format a number as ordinal (e.g., 1st, 2nd, 3rd).
     *
     * @param int $number
     * @return string
     */
    public function ordinal(int $number): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::ORDINAL);
            return $formatter->format($number);
        }
        
        // Simple English fallback
        if ($this->locale === 'en_US' || $this->locale === 'en_GB') {
            $suffix = 'th';
            if ($number % 100 < 11 || $number % 100 > 13) {
                switch ($number % 10) {
                    case 1: $suffix = 'st'; break;
                    case 2: $suffix = 'nd'; break;
                    case 3: $suffix = 'rd'; break;
                }
            }
            return $number . $suffix;
        }
        
        // For Turkish
        if ($this->locale === 'tr_TR') {
            return $number . '.';
        }
        
        // Default
        return $number . '.';
    }

    /**
     * Convert a number to roman numerals.
     *
     * @param int $number
     * @return string
     */
    public function roman(int $number): string
    {
        $romanNumerals = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        
        $result = '';
        foreach ($romanNumerals as $roman => $value) {
            $matches = intval($number / $value);
            $result .= str_repeat($roman, $matches);
            $number = $number % $value;
        }
        
        return $result;
    }

    /**
     * Check if a number is even.
     *
     * @param int $number
     * @return bool
     */
    public static function isEven(int $number): bool
    {
        return $number % 2 === 0;
    }

    /**
     * Check if a number is odd.
     *
     * @param int $number
     * @return bool
     */
    public static function isOdd(int $number): bool
    {
        return $number % 2 !== 0;
    }

    /**
     * Formats a number as money with the default locale settings.
     * 
     * @param float $number
     * @return string
     */
    public function money(float $number): string
    {
        // For backwards compatibility, money() is an alias for currency() without specified currency
        return $this->currency($number);
    }

    /**
     * Create a new Number instance.
     *
     * @param string $locale
     * @return static
     */
    public static function locale(string $locale): self
    {
        return new static($locale);
    }

    /**
     * Format bytes to human-readable file size.
     *
     * @param int $bytes The size in bytes
     * @param int $precision The number of decimal places to include
     * @return string
     */
    public function humanReadable(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $precision);
            return $formatter->format($bytes) . ' ' . $units[$pow];
        }
        
        // Fallback if Intl extension is not available
        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }
     
}