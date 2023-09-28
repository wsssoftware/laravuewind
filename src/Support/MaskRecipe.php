<?php

namespace Laravuewind\Support;

use Illuminate\Support\Collection;

abstract class MaskRecipe
{
    public static function create(): static
    {
        return new static();
    }

    abstract public function getRegexPattern(string $value): array|Collection;
}