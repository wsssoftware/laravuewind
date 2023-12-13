<?php

namespace Laravuewind\Support;

interface WithFilters
{

    /**
     * @return \UnitEnum[]
     */
    public static function filteredCases(mixed $filter): array;
}
