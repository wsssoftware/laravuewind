<?php

namespace Laravuewind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laravuewind\Laravuewind
 */
class Laravuewind extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Laravuewind\Laravuewind::class;
    }
}
