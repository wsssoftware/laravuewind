<?php

namespace Laravuewind\FilePond;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class FilePondUploadedFile
{
    protected ?Closure $beforeMoveCallback = null;

    protected Filesystem|FilesystemAdapter $originalDisk;
    protected string $originalFilePath;

    public function __construct(
        protected Filesystem|FilesystemAdapter $disk,
        protected string $filePath
    ) {
        $this->originalDisk = $disk;
        $this->originalFilePath = $this->filePath;
    }

    public function beforeMove(Closure $callback): static
    {
        $this->beforeMoveCallback = $callback;
        return $this;
    }

    public function copy(string $to, string $targetDisk = null, mixed $options = []): bool
    {
        if ($targetDisk === null && $this->disk->copy($this->filePath, $to)) {
            return true;
        } elseif ($targetDisk !== null) {
            $targetDisk = Storage::disk($targetDisk);
            if ($targetDisk->put($to, $this->disk->get($this->filePath), $options)) {
                return true;
            }
        }
        return false;
    }

    public function disk(): Filesystem|FilesystemAdapter
    {
        return $this->disk;
    }

    public function filePath(): string
    {
        return $this->filePath;
    }

    public function get(): string|null
    {
        return $this->disk->get($this->filePath);
    }

    public function json(): array|null
    {
        return $this->disk->json($this->filePath);
    }

    public function mimeType(): string
    {
        return $this->disk->mimeType($this->filePath);
    }

    public function move(string $to, string $targetDisk = null, mixed $options = []): bool
    {
        if ($this->beforeMoveCallback instanceof Closure) {;
            $disk = !empty($targetDisk) ? Storage::disk($targetDisk) : $this->disk;
            if ($disk->put($to, $this->beforeMoveCallback->call($this, $this), $options)) {
                $this->removeUpload();
                return true;
            }
        } elseif ($targetDisk === null && $this->disk->move($this->filePath, $to)) {
            $this->removeUpload();
            $this->filePath = $to;
            return true;
        } elseif ($targetDisk !== null && $this->copy($to, $targetDisk, $options)) {
            $this->removeUpload();
            $this->disk->delete($this->filePath);
            $this->disk = Storage::disk($targetDisk);
            $this->filePath = $to;
            return true;
        }
        return false;
    }

    public function removeUpload(): void
    {
        $this->originalDisk->deleteDirectory(dirname($this->originalFilePath));
    }

    public function size(): int
    {
        return $this->disk->size($this->filePath);
    }
}