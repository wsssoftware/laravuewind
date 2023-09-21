<?php

namespace Laravuewind\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Laravuewind\FilePond\FilePondUploadedFile;

/**
 * @method static FilePondUploadedFile|Collection<int, \Laravuewind\FilePond\FilePondUploadedFile> getUpload(string|array|Collection $serverId, bool $alwaysCollection = false):
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
