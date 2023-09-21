<?php

namespace Laravuewind\FilePond;


use Illuminate\Support\Collection;

class FilePond
{
    public function __construct(protected FilePondFactory $factory)
    {
    }

    /**
     * @throws \Exception
     */
    public function getUpload(string|array|Collection $serverId, bool $alwaysCollection = false): FilePondUploadedFile|Collection
    {
        if (is_string($serverId)) {
            $collection = collect([$serverId]);
        } elseif (is_array($serverId)) {
            $collection = collect($serverId);
        } else {
            $collection = $serverId;
        }
        $collection = $collection
            ->ensure('string')
            ->map(function (string $serverId) {
                return new FilePondUploadedFile($this->factory->disk(), $this->factory->getServerId($serverId)->getFilePath());
            })
            ->ensure(FilePondUploadedFile::class);
        return $collection->count() > 1 || $alwaysCollection ? $collection : $collection->first();
    }
}
