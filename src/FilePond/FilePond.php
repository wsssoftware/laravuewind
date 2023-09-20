<?php

namespace Laravuewind\FilePond;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FilePond
{

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
        if (!isset(config('filesystems.disks')[$disk])) {
            throw new Exception("Disk [$disk] not found in filesystems config");
        }
        return $disk;
    }

    /**
     * @throws \Exception
     */
    public function getPath(string $uploadId): string
    {
        if (empty($uploadId)) {
            throw new Exception('Upload ID cannot be empty');
        }
        $path = Crypt::decryptString($uploadId);
        if (!str_starts_with($path, config('laravuewind.filepond.temporary_path', 'filepond'))) {
            throw new Exception('Invalid upload ID');
        }
        return $path;
    }

    public function getUploadId(string $path): string
    {
        return Crypt::encryptString($path);
    }
}
