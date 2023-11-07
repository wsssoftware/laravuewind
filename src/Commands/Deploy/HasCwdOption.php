<?php

namespace Laravuewind\Commands\Deploy;

use RuntimeException;

trait HasCwdOption
{
    protected function getCwdOption(): string
    {
        $cwd = $this->option('cwd') ?? app()->basePath();
        if (! is_dir($cwd)) {
            throw new RuntimeException("The directory $cwd does not exist");
        };
        return $cwd;
    }

}
