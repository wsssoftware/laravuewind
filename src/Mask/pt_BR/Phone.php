<?php

namespace Laravuewind\Mask\pt_BR;

use Illuminate\Support\Collection;
use Laravuewind\Support\MaskRecipe;

class Phone extends MaskRecipe
{

    public function getRegexPattern(string $value): array|Collection
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (preg_match('/^[1-9][0-9]9$/', substr($value,  0, 3)) === 1) {
            return ['/\d/', '/\d/', ' ', '/\d/', ' ', '/\d/', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/', '/\d/', '/\d/'];
        }
        if (preg_match('/^400$/', substr($value,  0, 3)) === 1) {
            return ['/\d/', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/', '/\d/', '/\d/'];
        }
        if (preg_match('/^0[3589]00$/', substr($value,  0, 4)) === 1) {
            return ['/\d/', '/\d/', '/\d/', '/\d/', ' ', '/\d/', '/\d/', '/\d/', ' ', '/\d/', '/\d/', '/\d/', '/\d/'];
        }
        if (preg_match('/^[1-9][0-9][1-5][0-9]$/', substr($value,  0, 4)) === 1) {
            return ['/\d/', '/\d/', ' ', '/\d/', '/\d/', '/\d/', '/\d/', '-', '/\d/', '/\d/', '/\d/', '/\d/'];
        }
        return ['/\d/', '/\d/', '/\d/'];
    }
}