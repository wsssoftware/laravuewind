<?php

namespace Laravuewind\FilePond;

use Illuminate\Support\Collection;

abstract class SaveRecipe
{

    private function __construct(
        protected FilePondUploadedFile $filePondUploadedFile
    ) {
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
        $implementations = class_implements($this);
        $name = null;
        $options = [];
        if (isset($implementations[WithBeforeStore::class])) {
            /** @var \Laravuewind\FilePond\SaveRecipe|\Laravuewind\FilePond\WithBeforeStore $this */
            $this->filePondUploadedFile->beforeStore($this->beforeStore());
        }
        if (isset($implementations[WithCustomFilename::class])) {
            /** @var \Laravuewind\FilePond\SaveRecipe|\Laravuewind\FilePond\WithCustomFilename $this */
            $name = $this->getFilename();
        }
        if (isset($implementations[WithOptions::class])) {
            /** @var \Laravuewind\FilePond\SaveRecipe|\Laravuewind\FilePond\WithOptions $this */
            $options = $this->options();
        }
        $toStore = $this->toStore();
        if ($toStore instanceof StoreItem) {
            $toStore = collect([$toStore]);
        }
        /** @var \Illuminate\Support\Collection<int, \Laravuewind\FilePond\StoreItem> $toStore */
        $toStore->ensure(StoreItem::class);

        $files = [];
        foreach ($toStore as $item) {
            $files[$item->id()] = $this->filePondUploadedFile->storeItem($item, $name, $options);
        }

        return $files;
    }
}
