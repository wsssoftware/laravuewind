<?php

namespace Laravuewind\Facades;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Facade;
use Laravuewind\FilePond\ServerId;

/**
 * @method static string createFolderId()
 * @method static ServerId createServerId(string $folderId, int $fileSize)
 * @method static Filesystem|FilesystemAdapter disk()
 * @method static string diskName()
 * @method static string getBasePath()
 * @method static ServerId getServerId(string $encrypted)
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
