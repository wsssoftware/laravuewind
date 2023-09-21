<?php

namespace Laravuewind\Commands;

use Illuminate\Console\Command;

class LaravuewindCommand extends Command
{
    public $signature = 'laravuewind';

    public $description = 'My command';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
