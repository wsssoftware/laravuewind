<?php

namespace Laravuewind\Facades;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Filesystem|FilesystemAdapter disk()
 * @method static string diskName()
 * @method static string getPath(string $uploadId)
 * @method static string getUploadId(string $path)
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
