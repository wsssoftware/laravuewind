<?php

namespace Laravuewind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string dsasa()
 *
 * @see \Laravuewind\FilePond\FilePond
 */
class FilePond extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laravuewind\FilePond\FilePond::class;
    }
}
