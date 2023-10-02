<?php

namespace Laravuewind\FilePond;

interface WithBeforeStore
{

    /**
     * Return a BeforeStore instance that will handle the file before storing it.
     */
    public function beforeStore(): BeforeStore;
}
