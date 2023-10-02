<?php

namespace Laravuewind\FilePond;

use Illuminate\Support\Collection;

abstract class SaveRecipe
{

    private function __construct(
        protected FilePondUploadedFile $filePondUploadedFile
    )
    {
        //
    }

    public static function fromFilePondUploadFile(FilePondUploadedFile $file): array|false
    {
        return (new static($file))->handle();
    }

    /**
     * Store one or more items.
     *
     * @return \Illuminate\Support\Collection<int, \Laravuewind\FilePond\StoreItem>|\Laravuewind\FilePond\StoreItem
     */
    abstract public function toStore(): Collection|StoreItem;

    protected function handle(): array|false
    {
        return [];
    }
}
