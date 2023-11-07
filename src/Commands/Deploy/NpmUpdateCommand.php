<?php

namespace Laravuewind\Commands\Deploy;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class NpmUpdateCommand
 *
 * Created by allancarvalho in setembro 13, 2022
 */
class NpmUpdateCommand extends Command
{
    use HasCwdOption;
    use FakeRun;
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
        if (self::$fake) {
            return self::SUCCESS;
        }
        $this->components->info('Updating npm packages');
        $process = new Process(['npm', 'update'],  $this->getCwdOption(), timeout: 300);
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->getOutput()->write($data);
            } else {
                $this->getOutput()->warning($data);
            }
        }

        return self::SUCCESS;
    }
}
