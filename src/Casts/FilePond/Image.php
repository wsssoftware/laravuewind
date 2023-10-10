<?php

namespace Laravuewind\Casts\FilePond;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

readonly class Image implements Arrayable
{
    public string $disk;

    public string $path;

    public string $url;

    public function __construct(array $data)
    {
        $this->disk = $data['disk'];
        $this->path = $data['path'];
        $this->url = Storage::disk($data['disk'])->url($data['path']);
    }

    public function toArray(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'url' => $this->url,
        ];
    }
}
