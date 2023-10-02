<?php

namespace Laravuewind\FilePond;

interface WithCustomFilename
{
    /**
     * Get a random filename (without extension) for storage file.
     */
    public function getFilename(): string;
}
