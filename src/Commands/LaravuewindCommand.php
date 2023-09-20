<?php

namespace Laravuewind\Commands;

use Illuminate\Console\Command;
use Laravuewind\Facades\FilePond;

class LaravuewindCommand extends Command
{
    public $signature = 'laravuewind';

    public $description = 'My command';

    public function handle(): int
    {
        ray(FilePond::disk());

        return self::SUCCESS;
    }
}
