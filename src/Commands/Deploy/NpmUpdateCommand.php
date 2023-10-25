<?php

namespace Laravuewind\Commands\Deploy;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Process\Process;

/**
 * Class NpmUpdateCommand
 *
 * Created by allancarvalho in setembro 13, 2022
 */
class NpmUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npm:update {--c|cwd= : Current work directory for command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the npm packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Updating npm packages');

        $cwd = $this->option('cwd') ?? dirname(__DIR__, 6);
        if (! is_dir($cwd)) {
            $this->components->error("The directory $cwd does not exist");

            return SymfonyCommand::FAILURE;
        }
        $process = new Process(['npm', 'update'], $cwd);
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->getOutput()->write($data);
            } else {
                $this->getOutput()->warning($data);
            }
        }

        return SymfonyCommand::SUCCESS;
    }
}
