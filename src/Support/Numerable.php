<?php

namespace Laravuewind\Support;

class Numerable
{

    protected float|int $value;

    /**
     * Create a new instance of the class.
     *
     * @param  string|float|int  $value
     */
    public function __construct(string|float|int $value = '')
    {
        $value = Number::parse($value);
        if ($value === false) {
            throw new \InvalidArgumentException('The value must be a valid number.');
        }
        $this->value = $value;
    }

    /**
     * Get the number as a float.
     */
    public function toFloat(): float
    {
        return floatval($this->value);
    }

    /**
     * Get the number as an integer.
     */
    public function toInteger(int $mode = null): int
    {
        return $mode === null ? intval($this->value) : round($this->value, mode: $mode);
    }

    /**
     * Get the number as a float percentage.
     */
    public function toPercentage(): float
    {
        return $this->toFloat() / 100;
    }

}