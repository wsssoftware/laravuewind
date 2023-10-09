<?php

namespace Laravuewind\Casts\FilePond;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @property Image $original
 * @property Image $default
 * @property Image $thumbnail
 * @property Image $thumb
 * @property Image $small
 * @property Image $sm
 * @property Image $large
 * @property Image $lg
 * @property Image $medium
 * @property Image $md
 */
readonly class Images implements Arrayable
{
    public function __construct(protected array $data)
    {
    }

    public function __get(string $name): Image
    {
        return $this->get($name);
    }

    public function get(string $name): Image
    {
        if (! isset($this->data[$name])) {
            throw new \InvalidArgumentException("\"$name\" image does not exist.");
        }
        return new Image($this->data[$name]);
    }

    public function toArray(): array
    {
        $images = [];
        foreach ($this->data as $key => $item) {
            $images[$key] = (new Image($item))->toArray();
        }
        return $images;
    }
}
