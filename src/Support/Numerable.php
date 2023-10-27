<?php

namespace Laravuewind\Support;

class Numerable
{
    protected float|int $value;

    /**
     * Create a new instance of the class.
     */
    public function __construct(string|float|int $value = '')
    {
        $value = Number::parse($value);
        if ($value === false || $value === null) {
            throw new \InvalidArgumentException('The value must be a valid number.');
        }
        $this->value = $value;
    }

    /**
     * Add the given values to the number.
     */
    public function add(int|float|string ...$values): static
    {
        $numbers = array_map(function ($value) {
            return (new static($value))->toRaw();
        }, $values);

        return new static(Number::sum($this->value, ...$numbers));
    }

    public function equalsTo(string|int|float $value): bool
    {
        return $this->toFloat() === (new static($value))->toFloat();
    }

    public function isFloat(): bool
    {
        return is_float($this->value);
    }

    public function isInteger(): bool
    {
        return is_int($this->value);
    }

    /**
     * Parse to a float instance.
     */
    public function parseToFloat(): static
    {
        return new static(Number::toFloat($this->value));
    }

    /**
     * Parse to an integer instance.
     */
    public function parseToInteger(int $mode = null): static
    {
        return new static(Number::toInteger($this->value, $mode));
    }

    /**
     * Convert the number to a percentage.
     */
    public function parseToPercentage(): static
    {
        return new static(Number::parseToPercentage($this->value));
    }

    /**
     * Subtract the given values from the number.
     */
    public function sub(int|float|string ...$values): static
    {
        $numbers = array_map(function ($value) {
            return (new static($value))->toRaw();
        }, $values);

        return new static($this->value - Number::sum(...$numbers));
    }

    /**
     * Get the number as a float.
     */
    public function toFloat(): float
    {
        return Number::toFloat($this->value);
    }

    /**
     * Get the number as an integer.
     */
    public function toInteger(int $mode = null): int
    {
        return Number::toInteger($this->value, $mode);
    }

    /**
     * Return the raw number.
     */
    public function toRaw(): float|int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return Number::format($this->value);
    }
}
