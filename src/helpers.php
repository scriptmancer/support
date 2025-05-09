<?php

use Scriptmancer\Debug\Dumper;
use Scriptmancer\Support\DateObject;
use Scriptmancer\Support\Stringable;
use Scriptmancer\Support\Number;
use Scriptmancer\Support\Numeric;
use Scriptmancer\Support\Url;
use Scriptmancer\Support\Validation;
use Scriptmancer\Support\Domain;
use Scriptmancer\Support\Html;
use Scriptmancer\Support\File;

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }
        
        exit(1);
    }
}

if (!function_exists('d')) {
    /**
     * Dump the passed variables.
     *
     * @param  mixed  $args
     * @return void
     */
    function d(...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }
    }
}

if (!function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Scriptmancer\Collections\Collection
     */
    function collect($value = null)
    {
        return new \Scriptmancer\Collections\Collection($value);
    }
}

if (!function_exists('date_obj')) {
    /**
     * Create a new DateObject instance.
     *
     * @param  mixed  $date
     * @return \Scriptmancer\Support\DateObject
     */
    function date_obj($date = null)
    {
        return new DateObject($date);
    }
}

if (!function_exists('str')) {
    /**
     * Create a new Stringable instance.
     *
     * @param  string  $string
     * @return \Scriptmancer\Support\Stringable
     */
    function str(string $string = '')
    {
        return new Stringable($string);
    }
}

if (!function_exists('number')) {
    /**
     * Create a new Number instance.
     *
     * @param  string  $locale
     * @return \Scriptmancer\Support\Number
     */
    function number(string $locale = 'tr_TR')
    {
        return new Number($locale);
    }
}

if (!function_exists('numeric')) {
    /**
     * Create a new Numeric instance.
     *
     * @param  float|int  $value
     * @param  string  $locale
     * @return \Scriptmancer\Support\Numeric
     */
    function numeric($value = 0, string $locale = 'tr_TR')
    {
        return new Numeric($value, $locale);
    }
}

if (!function_exists('url')) {
    /**
     * Parse a URL.
     *
     * @param  string  $url
     * @return array|false
     */
    function url(string $url)
    {
        return Url::parse($url);
    }
}

if (!function_exists('validate')) {
    /**
     * Validate a value using the Validation class.
     *
     * @param  mixed  $value
     * @param  string  $rule
     * @param  mixed  ...$params
     * @return bool
     */
    function validate($value, string $rule, ...$params)
    {
        if (method_exists(Validation::class, $rule)) {
            return Validation::$rule($value, ...$params);
        }
        
        return false;
    }
}

if (!function_exists('domain')) {
    /**
     * Create a new Domain instance.
     *
     * @param  string  $value
     * @return \Scriptmancer\Support\Domain
     */
    function domain(string $value = '')
    {
        return new Domain($value);
    }
}

if (!function_exists('html')) {
    /**
     * Escape HTML entities in a string.
     *
     * @param  string  $value
     * @param  bool  $doubleEncode
     * @return string
     */
    function html(string $value, bool $doubleEncode = true): string
    {
        return Html::special($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', $doubleEncode);
    }
}

if (!function_exists('file_get')) {
    /**
     * Get the contents of a file.
     *
     * @param  string  $path
     * @param  bool  $lock
     * @return string|false
     */
    function file_get(string $path, bool $lock = false)
    {
        return File::get($path, $lock);
    }
}

if (!function_exists('collection_debug')) {
    /**
     * Get a debugger for the given collection.
     *
     * @param  mixed  $items
     * @return \Scriptmancer\Debug\CollectionDebugger
     */
    function collection_debug($items)
    {
        return collect($items)->debug();
    }
}

if (!function_exists('hash_value')) {
    /**
     * Hash the given value using the Hash class.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    function hash_value(string $value, array $options = [])
    {
        return \Scriptmancer\Support\Hash::make($value, $options);
    }
}

if (!function_exists('pipeline')) {
    /**
     * Create a new Pipeline instance.
     *
     * @param  mixed  $passable
     * @return \Scriptmancer\Support\Pipeline
     */
    function pipeline($passable = null)
    {
        return new \Scriptmancer\Support\Pipeline($passable);
    }
} 