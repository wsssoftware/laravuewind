<?php

namespace Laravuewind\FilePond;

abstract class StoreItem
{
    use ReceiveFilePondUploadedFile;

    private ?array $options = [];

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;


    /**
     * Upload name like 'default', 'large', 'thumb' or etc.
     */
    abstract public function name(): string;

    public function options(): array|null
    {
        return empty($this->options) ? null : $this->options;
    }

    /**
     * where to store the file in disk
     */
    abstract public function path(): string;

    public function withDisk(string $disk): self
    {
        $this->options['disk'] = $disk;
        return $this;
    }

    public function withOptions(string|array $options): self
    {
        $options = is_string($options) ? ['disk' => $options] : $options;
        $this->options = $options + $this->options;
        return $this;
    }

    public function withPublicVisibility(): self
    {
        $this->options['visibility'] = 'public';
        return $this;
    }
}
