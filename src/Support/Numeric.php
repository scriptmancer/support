<?php

namespace Nazim\Support;

class Numeric
{
    /**
     * Create a new Numeric instance.
     *
     * @param float|int $value The underlying numeric value
     * @param string $locale The locale to use for formatting
     */
    public function __construct(
        protected float|int $value = 0, 
        protected string $locale = 'tr_TR'
    ) {
    }

    /**
     * Return the underlying numeric value as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Get the underlying numeric value.
     *
     * @return float|int
     */
    public function toValue(): float|int
    {
        return $this->value;
    }

    /**
     * Create a new Numeric instance.
     *
     * @param float|int $value
     * @param string $locale
     * @return static
     */
    public static function of(float|int $value, string $locale = 'tr_TR'): self
    {
        return new static($value, $locale);
    }

    /**
     * Set the locale for this numeric instance.
     *
     * @param string $locale
     * @return static
     */
    public function locale(string $locale): self
    {
        return new static($this->value, $locale);
    }

    /**
     * Add a value to the current value.
     *
     * @param float|int $value
     * @return static
     */
    public function add(float|int $value): self
    {
        return new static($this->value + $value, $this->locale);
    }

    /**
     * Subtract a value from the current value.
     *
     * @param float|int $value
     * @return static
     */
    public function subtract(float|int $value): self
    {
        return new static($this->value - $value, $this->locale);
    }

    /**
     * Multiply the current value by another value.
     *
     * @param float|int $value
     * @return static
     */
    public function multiply(float|int $value): self
    {
        return new static($this->value * $value, $this->locale);
    }

    /**
     * Divide the current value by another value.
     *
     * @param float|int $value
     * @return static
     */
    public function divide(float|int $value): self
    {
        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }
        
        return new static($this->value / $value, $this->locale);
    }

    /**
     * Get the modulo of the current value.
     *
     * @param int $value
     * @return static
     */
    public function modulo(int $value): self
    {
        return new static($this->value % $value, $this->locale);
    }

    /**
     * Get the absolute value.
     *
     * @return static
     */
    public function abs(): self
    {
        return new static(abs($this->value), $this->locale);
    }

    /**
     * Round the value to the specified precision.
     *
     * @param int $precision
     * @return static
     */
    public function round(int $precision = 0): self
    {
        return new static(round($this->value, $precision), $this->locale);
    }

    /**
     * Round the value up to the nearest integer.
     *
     * @return static
     */
    public function ceil(): self
    {
        return new static(ceil($this->value), $this->locale);
    }

    /**
     * Round the value down to the nearest integer.
     *
     * @return static
     */
    public function floor(): self
    {
        return new static(floor($this->value), $this->locale);
    }

    /**
     * Get the minimum value between the current value and the given value.
     *
     * @param float|int $value
     * @return static
     */
    public function min(float|int $value): self
    {
        return new static(min($this->value, $value), $this->locale);
    }

    /**
     * Get the maximum value between the current value and the given value.
     *
     * @param float|int $value
     * @return static
     */
    public function max(float|int $value): self
    {
        return new static(max($this->value, $value), $this->locale);
    }

    /**
     * Determine if the value is even.
     *
     * @return bool
     */
    public function isEven(): bool
    {
        return Number::isEven((int) $this->value);
    }

    /**
     * Determine if the value is odd.
     *
     * @return bool
     */
    public function isOdd(): bool
    {
        return Number::isOdd((int) $this->value);
    }

    /**
     * Determine if the value is positive.
     *
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    /**
     * Determine if the value is negative.
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->value < 0;
    }

    /**
     * Determine if the value is zero.
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->value == 0;
    }

    /**
     * Format the number according to the current locale.
     *
     * @param int $decimals
     * @return string
     */
    public function format(int $decimals = 2): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->format($this->value, $decimals);
    }

    /**
     * Format the number as currency according to the current locale.
     *
     * @param string|null $currency
     * @return string
     */
    public function currency(?string $currency = null): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->currency($this->value, $currency);
    }

    /**
     * Format the number as percentage according to the current locale.
     *
     * @param int $decimals
     * @return string
     */
    public function percent(int $decimals = 1): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->percent($this->value, $decimals);
    }

    /**
     * Format the number in a human-readable way (e.g., 1K, 1M, 1B).
     *
     * @return string
     */
    public function shortForm(): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->shortForm($this->value);
    }

    /**
     * Format bytes to human-readable file size.
     *
     * @param int $precision
     * @return string
     */
    public function humanReadable(int $precision = 2): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->humanReadable((int) $this->value, $precision);
    }

    /**
     * Format the number using words.
     *
     * @return string
     */
    public function spellOut(): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->spellOut($this->value);
    }

    /**
     * Format the number as ordinal.
     *
     * @return string
     */
    public function ordinal(): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->ordinal((int) $this->value);
    }

    /**
     * Convert the number to roman numerals.
     *
     * @return string
     */
    public function roman(): string
    {
        $numberFormatter = new Number($this->locale);
        return $numberFormatter->roman((int) $this->value);
    }
} 