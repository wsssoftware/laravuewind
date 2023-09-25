<?php

namespace Laravuewind\FilePond;

abstract class StoreManyItem
{
    protected readonly FilePondUploadedFile $filePondUploadFile;

    private ?string $name = null;

    private array|null $options = null;

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;

    final public function name(): ?string
    {
        return $this->name;
    }

    final public function options(): string|array|null
    {
        return $this->options;
    }

    /**
     * where to store the file
     */
    abstract public function path(): string;

    final public function setFilePondUploadFile(FilePondUploadedFile $filePondUploadFile): self
    {
        $this->filePondUploadFile = $filePondUploadFile;

        return $this;
    }

    final public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    final public function withDisk(string $disk): self
    {
        if (empty($this->options)) {
            $this->options = [];
        }
        $this->options['disk'] = $disk;

        return $this;
    }

    final public function withOptions(string|array $options): self
    {
        $this->options = is_array($options) ? $options : ['disk' => $options];

        return $this;
    }

    final public function withPublicVisibility(): self
    {
        if (empty($this->options)) {
            $this->options = [];
        }
        $this->options['visibility'] = 'public';

        return $this;
    }
}
