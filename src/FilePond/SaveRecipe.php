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
        $toStore = $this->toStore();
        if ($toStore instanceof StoreItem) {
            $toStore = collect([$toStore]);
        }
        $this->checkIfIdIsUnique($toStore);
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

        $files = [];
        foreach ($toStore as $item) {
            $files[$item->id()] = $this->filePondUploadedFile->storeItem($item, $name, $options);
        }

        return $files;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \Laravuewind\FilePond\StoreItem>  $toStore
     */
    protected function checkIfIdIsUnique(Collection $toStore): void
    {
        $toStore->ensure(StoreItem::class);
        $usedIds = [];
        $toStore->each(function (StoreItem $item) use (&$usedIds) {
            if (in_array($item->id(), $usedIds)) {
                throw new \InvalidArgumentException('The id "'.$item->id().'" is not unique.');
            }
            $usedIds[] = $item->id();
        });

    }
}
