<?php

namespace Laravuewind\Support;

use Illuminate\Support\Collection;

class Mask
{
    protected string $value;

    protected Collection $regexPattern;

    private function __construct(
        Collection $regexPattern,
        string $value
    ) {
        if ($regexPattern->isEmpty() && empty($value)) {
            throw new \InvalidArgumentException('Regex pattern and/or value cannot be empty');
        }
        $this->validateRegexItems($regexPattern);
        $this->regexPattern = $regexPattern->values();
        $this->value = $value;
    }

    protected function isRegex(string $regex): bool
    {
        $regex = str($regex);

        return $regex->startsWith('/') && $regex->endsWith('/') && $regex->length() > 2;
    }

    public static function make(array|Collection|MaskRecipe $regexPattern, string|int|float|null $value): ?string
    {
        if (empty($value)) {
            return $value;
        }
        $value = strval($value);
        if ($regexPattern instanceof MaskRecipe) {
            $regexPattern = $regexPattern->getRegexPattern($value);
        }
        if (is_array($regexPattern)) {
            $regexPattern = collect($regexPattern);
        }

        return (new static($regexPattern, $value))->mask();
    }

    protected function mask(): string
    {
        $masked = '';
        $letters = collect(str_split($this->value));
        foreach ($this->regexPattern as $regex) {
            if ($this->isRegex($regex)) {
                if ($letters->isEmpty()) {
                    return $this->value;
                }
                $value = $letters->shift();
                if (preg_match($regex, $value) === 0) {
                    return $this->value;
                } else {
                    $masked .= $value;
                }
            } else {
                $masked .= $regex;
            }
        }

        return $masked;
    }

    protected function validateRegexItems(Collection $regexPattern): void
    {
        foreach ($regexPattern as $index => $item) {
            $regex = str($item);
            if (! $this->isRegex($regex)) {
                continue;
            }
            if (@preg_match($regex->toString(), '') === false) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid regex pattern "%s" at index %d',
                    $regex->toString(),
                    $index
                ));
            }
        }
    }
}
