<?php

namespace Nazim\Support;
use Nazim\Support\Date;

class DateObject
{
    /**
     * The underlying DateTime instance.
     *
     * @var \DateTime
     */
    public $date;

    /**
     * Create a new DateObject instance.
     *
     * @param mixed $date DateTime, DateObject, string, or null for current time
     */
    public function __construct($date = null)
    {
        if ($date instanceof DateObject) {
            $this->date = clone $date->date;
        } elseif ($date instanceof \DateTime) {
            $this->date = clone $date;
        } else {
            $this->date = $date !== null ? Date::parse($date) : Date::now();
        }
    }

    /**
     * Dynamically call methods on the Date class.
     *
     * @param string $name The method name
     * @param array $arguments The method arguments
     * @return mixed|static
     */
    public function __call($name, $arguments)
    {
        // If method does not exist, return null
        if (!method_exists(Date::class, $name)) {
            return null;
        }
        
        // First argument is the current date object instance
        $args = array_merge([$this->date], $arguments);
        
        // For methods that accept another DateObject as a parameter (like diff, isSameDay, etc.),
        // we need to extract the DateTime object from the DateObject
        foreach ($args as $key => $arg) {
            if ($arg instanceof DateObject) {
                $args[$key] = $arg->date;
            }
        }
        
        $result = Date::$name(...$args);
        
        // If the result is a DateTime object, wrap it in a DateObject
        if ($result instanceof \DateTime) {
            return new static($result);
        }
        
        return $result;
    }
    
    /**
     * Convert the DateObject to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return Date::formatToString($this->date);
    }

    /**
     * Create a new DateObject instance.
     *
     * @param mixed $date
     * @return static
     */
    public static function make($date = null)
    {
        return new static($date);
    }

    /**
     * Create a DateObject for the current date and time.
     *
     * @return static
     */
    public static function now()
    {
        return new static(Date::now());
    }

    /**
     * Create a DateObject for today.
     *
     * @return static
     */
    public static function today()
    {
        return new static(Date::today());
    }

    /**
     * Create a DateObject for yesterday.
     *
     * @return static
     */
    public static function yesterday()
    {
        return new static(Date::yesterday());
    }

    /**
     * Create a DateObject for tomorrow.
     *
     * @return static
     */
    public static function tomorrow()
    {
        return new static(Date::tomorrow());
    }
}