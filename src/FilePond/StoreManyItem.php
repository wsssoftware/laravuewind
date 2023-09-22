<?php

namespace Laravuewind\FilePond;

abstract class StoreManyItem
{

    protected readonly FilePondUploadedFile $filePondUploadFile;

    private string|null $name = null;

    private string|array|null $options = null;

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;

    final public function name(): string|null
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

    final public function setFilePondUploadFile (FilePondUploadedFile $filePondUploadFile): self
    {
        $this->filePondUploadFile = $filePondUploadFile;
        return $this;
    }

    final public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    final public function withOptions(string|array $options): self
    {
        $this->options = $options;
        return $this;
    }
}