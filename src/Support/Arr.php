<?php

namespace Nazim\Support;

class Arr
{
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        return $array[$key] ?? $default;
    }

    public static function set(array $array, string $key, mixed $value): array
    {
        $array[$key] = $value;
        return $array;
    }

    public static function has(array $array, string $key): bool
    {
        return array_key_exists($key, $array);
    }
    
    public static function last(array $array): mixed
    {
        return empty($array) ? null : end($array);
    }

    public static function first(array $array): mixed
    {
        return empty($array) ? null : reset($array);
    }

    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    public static function values(array $array): array
    {
        return array_values($array);
    }

    public static function count(array $array): int
    {
        return count($array);
    }

    public static function map(array $array, callable $callback): array
    {
        return array_map($callback, $array);
    }

    
    public static function filter(array $array, callable $callback): array
    {
        return array_filter($array, $callback);
    }

    public static function reduce(array $array, callable $callback, $initial = null)
    {
        return array_reduce($array, $callback, $initial);
    }

    public static function sort(array $array, int $flags = SORT_REGULAR): array
    {
        sort($array, $flags);
        return $array;
    }

    public static function reverse(array $array): array
    {
        return array_reverse($array);
    }

    public static function chunk(array $array, int $size): array
    {
        return array_chunk($array, $size);
    }

    // Maybe this should have more than two arrays?
    public static function merge(array $array1, array $array2): array
    {
        return array_merge($array1, $array2);
    }

    public static function slice(array $array, int $offset, int $length = null): array
    {
        return array_slice($array, $offset, $length);
    }

    /**
     * Push one or more values onto the end of an array.
     *
     * @param array $array
     * @param mixed ...$values
     * @return array
     */
    public static function push(array $array, ...$values): array
    {
        array_push($array, ...$values);
        return $array;
    }
    
    /**
     * Remove and return the last value from an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function pop(array &$array)
    {
        return array_pop($array);
    }
    
    /**
     * Prepend one or more values to the beginning of an array.
     *
     * @param array $array
     * @param mixed ...$values
     * @return array
     */
    public static function prepend(array $array, ...$values): array
    {
        array_unshift($array, ...$values);
        return $array;
    }
    
    /**
     * Remove and return the first value from an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function shift(array &$array)
    {
        return array_shift($array);
    }
    
    /**
     * Get an array of unique values.
     *
     * @param array $array
     * @return array
     */
    public static function unique(array $array): array
    {
        return array_unique($array);
    }
    
    /**
     * Get a random value from an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function random(array $array)
    {
        return $array[array_rand($array)];
    }
    
    /**
     * Get multiple random values from an array.
     *
     * @param array $array
     * @param int $number
     * @return array
     */
    public static function randomValues(array $array, int $number): array
    {
        $keys = array_rand($array, min($number, count($array)));
        
        return array_map(function ($key) use ($array) {
            return $array[$key];
        }, (array) $keys);
    }
    
    /**
     * Sort an array of strings or objects using locale-specific sorting rules.
     *
     * @param array $array The array to sort
     * @param string $locale The locale to use for sorting (default: 'tr_TR')
     * @param string|callable|null $field If sorting objects/arrays, specify the field to sort by or a callback
     * @return array The sorted array
     */
    public static function string_sort(array $array, string $locale = 'tr_TR', $field = null): array
    {
        // Create a copy of the array to avoid modifying the original
        $result = $array;
        
        if (!class_exists('Collator')) {
            // Check if Intl extension is available
            // Fallback to regular sort if Collator is not available
            if ($field === null) {
                sort($result);
                return $result;
            } else {
                usort($result, function ($a, $b) use ($field) {
                    if (is_callable($field)) {
                        return strcmp($field($a), $field($b));
                    } elseif (is_array($a) && is_array($b)) {
                        return strcmp($a[$field] ?? '', $b[$field] ?? '');
                    } elseif (is_object($a) && is_object($b)) {
                        return strcmp($a->$field ?? '', $b->$field ?? '');
                    }
                    return 0;
                });
                return $result;
            }
        }

        // Use Collator for locale-specific sorting
        $collator = new \Collator($locale);
        
        // If we have a field to sort by (for arrays of arrays or objects)
        if ($field !== null) {
            usort($result, function ($a, $b) use ($collator, $field) {
                if (is_callable($field)) {
                    return $collator->compare($field($a), $field($b));
                } elseif (is_array($a) && is_array($b)) {
                    return $collator->compare($a[$field] ?? '', $b[$field] ?? '');
                } elseif (is_object($a) && is_object($b)) {
                    return $collator->compare($a->$field ?? '', $b->$field ?? '');
                }
                return 0;
            });
        } else {
            // For simple arrays of strings
            usort($result, function ($a, $b) use ($collator) {
                return $collator->compare($a, $b);
            });
        }
        
        return $result;
    }

    /**
     * Sort an associative array by keys using locale-specific sorting rules.
     *
     * @param array $array The array to sort
     * @param string $locale The locale to use for sorting (default: 'tr_TR')
     * @return array The sorted array
     */
    public static function string_sort_by_key(array $array, string $locale = 'tr_TR'): array
    {
        if (!class_exists('Collator')) {
            ksort($array);
            return $array;
        }
        
        // Use Collator for locale-specific sorting
        $collator = new \Collator($locale);
        $keys = array_keys($array);
        
        // Sort keys using the collator
        usort($keys, function ($a, $b) use ($collator) {
            return $collator->compare($a, $b);
        });
        
        // Rebuild the array with sorted keys
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }
        
        return $result;
    }

    /**
     * Sort an array by keys.
     *
     * @param array $array The input array
     * @return array The sorted array
     */
    public static function sortByKey(array $array): array
    {
        ksort($array);
        return $array;
    }
    
    /**
     * Recursively merge arrays.
     *
     * @param array ...$arrays The arrays to merge
     * @return array The merged array
     */
    public static function mergeRecursive(...$arrays): array
    {
        return array_merge_recursive(...$arrays);
    }
    
    /**
     * Convert an array into a query string.
     *
     * @param array $array
     * @return string
     */
    public static function query(array $array): string
    {
        return http_build_query($array);
    }
    
    /**
     * Check if all elements in the array pass a given truth test.
     *
     * @param array $array
     * @param callable $callback
     * @return bool
     */
    public static function every(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if any element in the array passes a given truth test.
     *
     * @param array $array
     * @param callable $callback
     * @return bool
     */
    public static function some(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Group the array items by a given key.
     *
     * @param array $array
     * @param string|callable $key
     * @return array
     */
    public static function groupBy(array $array, $key): array
    {
        $result = [];
        
        foreach ($array as $item) {
            $groupKey = is_callable($key) ? $key($item) : $item[$key];
            
            if (!isset($result[$groupKey])) {
                $result[$groupKey] = [];
            }
            
            $result[$groupKey][] = $item;
        }
        
        return $result;
    }
    
    /**
     * Check if a value exists in an array.
     *
     * @param array $array
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public static function contains(array $array, $value, bool $strict = false): bool
    {
        return in_array($value, $array, $strict);
    }
    
    /**
     * Get the difference of arrays.
     *
     * @param array $array
     * @param array ...$arrays
     * @return array
     */
    public static function diff(array $array, array ...$arrays): array
    {
        return array_diff($array, ...$arrays);
    }

    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrap($value): array
    {
        if (is_null($value)) {
            return [];
        }
        
        return is_array($value) ? $value : [$value];
    }
}