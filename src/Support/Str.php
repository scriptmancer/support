<?php

namespace Scriptmancer\Support;

class Str
{
    /**
     * Determine if a string starts with a given substring.
     *
     * @param string $haystack The string to search in
     * @param string $needle The substring to search for
     * @return bool
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * Determine if a string ends with a given substring.
     *
     * @param string $haystack The string to search in
     * @param string $needle The substring to search for
     * @return bool
     */
    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }
    
    /**
     * Determine if a string contains a given substring.
     *
     * @param string $haystack The string to search in
     * @param string $needle The substring to search for
     * @return bool
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    /**
     * Get the length of a string.
     *
     * @param string $string The input string
     * @return int
     */
    public static function length(string $string): int
    {
        return strlen($string);
    }

    /**
     * Replace all occurrences of the search string with the replacement string.
     *
     * @param string $search The value being searched for
     * @param string $replace The replacement value
     * @param string $subject The string being searched and replaced on
     * @return string
     */
    public static function replace(string $search, string $replace, string $subject): string
    {
        return str_replace($search, $replace, $subject);
    }

    /**
     * Convert a string to lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function lower(string $string): string
    {
        return strtolower($string);
    }
    
    /**
     * Convert a string to uppercase.
     *
     * @param string $string
     * @return string
     */
    public static function upper(string $string): string
    {
        return strtoupper($string);
    }
    
    /**
     * Convert a string to title case.
     *
     * @param string $string
     * @return string
     */
    public static function title(string $string): string
    {
        return ucwords($string);
    }
    
    /**
     * Capitalize the first character of a string.
     *
     * @param string $string
     * @return string
     */
    public static function ucfirst(string $string): string
    {
        return ucfirst($string);
    }
    
    /**
     * Get a substring from a string.
     *
     * @param string $string
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return substr($string, $start, $length);
    }
    
    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
    public static function replaceFirst(string $search, string $replace, string $subject): string
    {
        if ($search === '') {
            return $subject;
        }
        
        $position = strpos($subject, $search);
        
        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }
        
        return $subject;
    }
    
    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        if ($search === '') {
            return $subject;
        }
        
        $position = strrpos($subject, $search);
        
        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }
        
        return $subject;
    }
    
    /**
     * Determine if a string matches a given pattern.
     *
     * @param string|array $pattern
     * @param string $value
     * @return bool
     */
    public static function is(string|array $pattern, string $value): bool
    {
        $patterns = is_array($pattern) ? $pattern : [$pattern];
        
        foreach ($patterns as $pattern) {
            if ($pattern === $value) {
                return true;
            }
            
            $pattern = preg_quote($pattern, '#');
            
            // Asterisks are translated into zero-or-more regular expression wildcards
            $pattern = str_replace('\*', '.*', $pattern);
            
            if (preg_match('#^' . $pattern . '\z#u', $value) === 1) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Limit the number of characters in a string.
     *
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
    
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param int $length
     * @return string
     */
    public static function random(int $length = 16): string
    {
        $string = '';
        
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            
            $bytes = random_bytes($size);
            
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        
        return $string;
    }
    
    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter
     * @return string
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        $key = $value;
        
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        
        return $value;
    }
    
    /**
     * Convert a string to camel case.
     *
     * @param string $value
     * @return string
     */
    public static function camel(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        
        return lcfirst(str_replace(' ', '', $value));
    }
    
    /**
     * Convert a string to kebab case.
     *
     * @param string $value
     * @return string
     */
    public static function kebab(string $value): string
    {
        return self::snake($value, '-');
    }
    
    /**
     * Pad both sides of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padBoth(string $value, int $length, string $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_BOTH);
    }
    
    /**
     * Pad the left side of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padLeft(string $value, int $length, string $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_LEFT);
    }
    
    /**
     * Pad the right side of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padRight(string $value, int $length, string $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_RIGHT);
    }
}