<?php

namespace Laravuewind\Mask\pt_BR;

use Illuminate\Support\Collection;
use Laravuewind\Support\MaskRecipe;

class Document extends MaskRecipe
{
    public function getRegexPattern(string $value): array|Collection
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (strlen($value) === 14) {
            return ['/\d/', '/\d/', '.', '/\d/', '/\d/', '/\d/', '.', '/\d/', '/\d/', '/\d/', '/', '/\d/', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/'];
        }

        return ['/\d/', '/\d/', '/\d/', '.', '/\d/', '/\d/', '/\d/', '.', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/'];

    }
}
