<?php

namespace Laravuewind\Commands\Deploy;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Process\Process;

/**
 * Class ViteBuildCommand
 *
 * Created by allancarvalho in setembro 19, 2022
 */
class ViteBuildCommand extends Command
{
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
        $cwd = $this->option('cwd') ?? dirname(__DIR__, 6);
        $this->components->info(sprintf(
            'Building resources on %s',
            $cwd,
        ));

        if (! is_dir($cwd)) {
            $this->components->error("The directory $cwd does not exist");

            return SymfonyCommand::FAILURE;
        }

        $process = new Process(['npm', 'run', 'build'], $cwd);
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->getOutput()->write($data);
            } else { // $process::ERR === $type
                $this->getOutput()->warning($data);
            }
        }

        return SymfonyCommand::SUCCESS;
    }
}
