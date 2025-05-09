<?php

namespace Scriptmancer\Support;

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
 
    /**
     * Flatten a multi-dimensional array into a single level.
     * @param array $array
     * @param int|float $depth
     * @return array
     */
    public static function flatten(array $array, int|float $depth = INF): array
    {
        $result = [];
        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } elseif ($depth === 1) {
                $result = array_merge($result, $item);
            } else {
                $result = array_merge($result, self::flatten($item, is_infinite($depth) ? $depth : $depth - 1));
            }
        }
        return $result;
    }

    /**
     * Convert a multi-dimensional associative array to dot notation.
     * @param array $array
     * @param string $prepend
     * @return array
     */
    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results += self::dot($value, $prepend.$key.'.');
            } else {
                $results[$prepend.$key] = $value;
            }
        }
        return $results;
    }

    /**
     * Convert a dot notation array into a multi-dimensional associative array.
     * @param array $array
     * @return array
     */
    public static function undot(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            self::setDot($result, $key, $value);
        }
        return $result;
    }

    protected static function setDot(array &$array, string $key, $value): void
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $k = array_shift($keys);
            if (!isset($array[$k]) || !is_array($array[$k])) {
                $array[$k] = [];
            }
            $array = &$array[$k];
        }
        $array[array_shift($keys)] = $value;
    }

    /**
     * Pluck an array of values for a given key from an array.
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function pluck(array $array, string $key): array
    {
        $results = [];
        foreach ($array as $item) {
            if (is_array($item) && array_key_exists($key, $item)) {
                $results[] = $item[$key];
            } elseif (is_object($item) && isset($item->$key)) {
                $results[] = $item->$key;
            }
        }
        return $results;
    }

    /**
     * Return all items except for the specified keys.
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function except(array $array, $keys): array
    {
        $keys = (array)$keys;
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * Return only the specified keys from the array.
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function only(array $array, $keys): array
    {
        $keys = (array)$keys;
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Remove a key (or nested key) from an array.
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function forget(array $array, string $key): array
    {
        $keys = explode('.', $key);
        $ref = &$array;
        while (count($keys) > 1) {
            $k = array_shift($keys);
            if (!isset($ref[$k]) || !is_array($ref[$k])) {
                return $array;
            }
            $ref = &$ref[$k];
        }
        unset($ref[array_shift($keys)]);
        return $array;
    }

    /**
     * Get a value and remove it from the array.
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function pull(array &$array, string $key, $default = null): mixed
    {
        $value = $array[$key] ?? $default;
        unset($array[$key]);
        return $value;
    }

    /**
     * Get one or more random values from the array.
     * @param array $array
     * @param int $number
     * @return mixed
     */
    public static function random(array $array, int $number = 1): mixed
    {
        $keys = array_rand($array, $number);
        if ($number === 1) return $array[$keys];
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Shuffle the array.
     * @param array $array
     * @return array
     */
    public static function shuffle(array $array): array
    {
        shuffle($array);
        return $array;
    }

    /**
     * Check if the array is associative.
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * Collapse an array of arrays into a single array.
     * @param array $array
     * @return array
     */
    public static function collapse(array $array): array
    {
        $results = [];
        foreach ($array as $values) {
            if (is_array($values)) {
                $results = array_merge($results, $values);
            }
        }
        return $results;
    }

    /**
     * Partition the array into two arrays by a callback.
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function partition(array $array, callable $callback): array
    {
        $truthy = $falsy = [];
        foreach ($array as $key => $item) {
            if ($callback($item, $key)) {
                $truthy[$key] = $item;
            } else {
                $falsy[$key] = $item;
            }
        }
        return [$truthy, $falsy];
    }

    /**
     * Filter array by a callback (preserve keys).
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function where(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }


    /**
     * Sort array by callback.
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function sortBy(array $array, callable $callback): array
    {
        uasort($array, function($a, $b) use ($callback) {
            return $callback($a, $b);
        });
        return $array;
    }

    /**
     * Check if any of the given keys exist in the array.
     * @param array $array
     * @param array $keys
     * @return bool
     */
    public static function hasAny(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if all of the given keys exist in the array.
     * @param array $array
     * @param array $keys
     * @return bool
     */
    public static function hasAll(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }

}