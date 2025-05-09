<?php

namespace Scriptmancer\Support;

class Stringable
{
    /**
     * Create a new Stringable instance.
     *
     * @param string $value The underlying string value
     */
    public function __construct(
        protected string $value = ''
    ) {
    }

    /**
     * Dynamically call methods on the Str class with the string as the first argument.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed|static
     */
    public function __call(string $method, array $arguments): mixed
    {
        // If method doesn't exist, return null
        if (!method_exists(Str::class, $method)) {
            return null;
        }

        // Add the string value as the first argument
        array_unshift($arguments, $this->value);

        // Call the method on the Str class
        $result = Str::$method(...$arguments);

        // If the result is a string, wrap it in a new Stringable instance
        if (is_string($result)) {
            return new static($result);
        }

        return $result;
    }

    /**
     * Return the underlying string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Get the underlying string value.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Create a new Stringable instance.
     *
     * @param string $value
     * @return static
     */
    public static function of(string $value): self
    {
        return new static($value);
    }

    /**
     * Determine if the string is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    /**
     * Determine if the string is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return $this->value !== '';
    }

    /**
     * Get the length of the string.
     *
     * @return int
     */
    public function length(): int
    {
        return Str::length($this->value);
    }

    /**
     * Determine if the string starts with the given value.
     *
     * @param string $needle
     * @return bool
     */
    public function startsWith(string $needle): bool
    {
        return Str::startsWith($this->value, $needle);
    }

    /**
     * Determine if the string ends with the given value.
     *
     * @param string $needle
     * @return bool
     */
    public function endsWith(string $needle): bool
    {
        return Str::endsWith($this->value, $needle);
    }

    /**
     * Determine if the string contains the given value.
     *
     * @param string $needle
     * @return bool
     */
    public function contains(string $needle): bool
    {
        return Str::contains($this->value, $needle);
    }

    /**
     * Convert the string to lowercase.
     *
     * @return static
     */
    public function lower(): self
    {
        return new static(Str::lower($this->value));
    }

    /**
     * Convert the string to uppercase.
     *
     * @return static
     */
    public function upper(): self
    {
        return new static(Str::upper($this->value));
    }

    /**
     * Convert the string to title case.
     *
     * @return static
     */
    public function title(): self
    {
        return new static(Str::title($this->value));
    }

    /**
     * Replace occurrences of the search string with the replacement string.
     *
     * @param string $search
     * @param string $replace
     * @return static
     */
    public function replace(string $search, string $replace): self
    {
        return new static(Str::replace($search, $replace, $this->value));
    }

    /**
     * Get a substring from the specified string.
     *
     * @param int $start
     * @param int|null $length
     * @return static
     */
    public function substr(int $start, int $length = null): self
    {
        return new static(Str::substr($this->value, $start, $length));
    }

    /**
     * Trim the string of the given characters.
     *
     * @param string $characters
     * @return static
     */
    public function trim(string $characters = " \t\n\r\0\x0B"): self
    {
        return new static(trim($this->value, $characters));
    }

    /**
     * Convert the string to snake case.
     *
     * @param string $delimiter
     * @return static
     */
    public function snake(string $delimiter = '_'): self
    {
        return new static(Str::snake($this->value, $delimiter));
    }

    /**
     * Convert the string to camel case.
     *
     * @return static
     */
    public function camel(): self
    {
        return new static(Str::camel($this->value));
    }

    /**
     * Convert the string to kebab case.
     *
     * @return static
     */
    public function kebab(): self
    {
        return new static(Str::kebab($this->value));
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param int $limit
     * @param string $end
     * @return static
     */
    public function limit(int $limit = 100, string $end = '...'): self
    {
        return new static(Str::limit($this->value, $limit, $end));
    }

    /**
     * Get the string as an array of characters.
     *
     * @return array
     */
    public function chars(): array
    {
        return str_split($this->value);
    }

    /**
     * Get the number of words in the string.
     *
     * @return int
     */
    public function wordCount(): int
    {
        return str_word_count($this->value);
    }
}
