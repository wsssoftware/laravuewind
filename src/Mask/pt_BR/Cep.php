<?php

namespace Laravuewind\Mask\pt_BR;

use Illuminate\Support\Collection;
use Laravuewind\Support\MaskRecipe;

class Cep extends MaskRecipe
{

    public function getRegexPattern(string $value): array|Collection
    {
        return  ['/\d/', '/\d/', '.', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/', '/\d/'];
    }
}