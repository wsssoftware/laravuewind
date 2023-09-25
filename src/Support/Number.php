<?php

namespace Laravuewind\Support;

use NumberFormatter;

class Number
{

    public static function of(string|float|int $value): Numerable
    {
        return new Numerable($value);
    }

    public static function parse(string|float|int $value, string $locale = null): float|int|false
    {
        $value = is_string($value) ? preg_replace('/[^0-9,.]+/', '', $value) : $value;
        $formatter = new NumberFormatter( $locale ?? config('app.locale', 'en'), NumberFormatter::DECIMAL );
        return $formatter->parse($value);
    }

    public static function toFloat(string|float|int $value): float
    {
        return (new Numerable($value))->toFloat();
    }

    public static function toInteger(string|float|int $value, int $mode = null): int
    {
        return (new Numerable($value))->toInteger($mode);
    }

    public static function toPercentage(string|float|int $value): float
    {
        return (new Numerable($value))->toPercentage();
    }
}