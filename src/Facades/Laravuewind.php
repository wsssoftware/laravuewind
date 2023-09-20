<?php

namespace Laravuewind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laravuewind\Laravuewind
 */
class Laravuewind extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laravuewind\Laravuewind::class;
    }
}
