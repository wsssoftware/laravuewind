<?php

namespace Laravuewind\FilePond;

abstract class BeforeStore
{
    protected readonly FilePondUploadedFile $filePondUploadFile;

    final public function setFilePondUploadFile (FilePondUploadedFile $filePondUploadFile): self
    {
        $this->filePondUploadFile = $filePondUploadFile;
        return $this;
    }

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;
}