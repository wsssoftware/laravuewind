<?php

namespace Laravuewind\FilePond;

abstract class StoreItem implements WithOptions
{
    use HasOptions;
    use ReceiveFilePondUploadedFile;

    /**
     * Upload id like 'default', 'large', 'thumb' or etc.
     */
    abstract public function id(): string;

    /**
     * handle files modifications and return it's content string
     */
    abstract public function handle(): string;

    /**
     * where to store the file in disk
     */
    abstract public function path(): string;
}
