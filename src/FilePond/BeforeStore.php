<?php

namespace Laravuewind\FilePond;

abstract class BeforeStore
{
    use ReceiveFilePondUploadedFile;

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;
}
