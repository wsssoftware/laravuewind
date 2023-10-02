<?php

namespace Laravuewind\FilePond;

trait ReceiveFilePondUploadedFile
{
    protected readonly FilePondUploadedFile $filePondUploadFile;

    public function setFilePondUploadFile(FilePondUploadedFile $filePondUploadFile): self
    {
        $this->filePondUploadFile = $filePondUploadFile;

        return $this;
    }
}
