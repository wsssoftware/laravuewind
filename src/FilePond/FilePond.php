<?php

namespace Laravuewind\FilePond;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilePond
{
    public function createFolderId(): string
    {
        return Str::random('32');
    }

    /**
     * @throws \Exception
     */
    public function createServerId(string $folderId, int $fileSize): ServerId
    {
        return ServerId::create($folderId, $fileSize);
    }

    /**
     * @throws \Exception
     */
    public function disk(): Filesystem|FilesystemAdapter
    {
        return Storage::disk($this->diskName());
    }

    /**
     * @throws \Exception
     */
    public function diskName(): string
    {
        $defaultDisk = config('filesystems.default');
        $disk = config('laravuewind.filepond.disk') ?? $defaultDisk;
        if (! isset(config('filesystems.disks')[$disk])) {
            throw new Exception("Disk [$disk] not found in filesystems config");
        }

        return $disk;
    }

    public function getBasePath(): string
    {
        return config('laravuewind.filepond.temporary_path', 'filepond');
    }

    /**
     * @throws \Exception
     */
    public function getServerId(string $encrypted): ServerId
    {
        return ServerId::decode($encrypted);
    }
}
