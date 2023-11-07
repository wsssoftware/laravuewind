<?php

namespace Laravuewind\Commands\Deploy;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class ViteBuildCommand
 *
 * Created by allancarvalho in setembro 19, 2022
 */
class ViteBuildCommand extends Command
{
    use HasCwdOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vite:build {--c|cwd= : Current work directory for command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build js and css files using vite';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cwd = $this->getCwdOption();
        $this->components->info(sprintf('Building resources on "%s"', $cwd));


        $process = new Process(['npm', 'run', 'build'], $cwd, timeout: 300);
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->getOutput()->write($data);
            } else { // $process::ERR === $type
                $this->getOutput()->warning($data);
            }
        }

        return self::SUCCESS;
    }
}
