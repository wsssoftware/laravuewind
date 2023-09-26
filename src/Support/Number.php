<?php

namespace Laravuewind\Support;

use NumberFormatter;

class Number
{

    public static function from(string|float|int $value): Numerable
    {
        return new Numerable($value);
    }

    public static function format(int|float $value): string
    {
        $formatter = new NumberFormatter($locale ?? config('app.locale', 'en'), NumberFormatter::DECIMAL);
        return $formatter->format($value);
    }

    public static function parse(string|float|int $value, string $locale = null): float|int|false
    {
        if (is_string($value)) {
            $formatter = new NumberFormatter($locale ?? config('app.locale', 'en'), NumberFormatter::DECIMAL);
            return $formatter->parse(preg_replace('/[^0-9,.]+/', '', $value));
        }
        return $value;
    }

    public static function parseToPercentage(int|float $value): float
    {
        return $value / 100;
    }

    public static function sum(int|float ...$values): int|float
    {
        return array_sum($values);
    }

    public static function toFloat(int|float $value): float
    {
        return is_float($value) ? $value : floatval($value);
    }

    public static function toInteger(int|float $value, int $mode = null): int
    {
        if (is_integer($value)) {
            return $value;
        }
        return intval($mode === null ? $value : round($value, mode: $mode));
    }
}