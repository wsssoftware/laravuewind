<?php

namespace Laravuewind\Commands\Deploy;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class Pm2RestartCommand
 *
 * Created by allancarvalho in janeiro 26, 2024
 */
class Pm2RestartCommand extends Command
{
    use HasCwdOption;
    use FakeRun;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pm2:restart {--c|cwd= : Current work directory for command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart PM2 processes.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (self::$fake) {
            return self::SUCCESS;
        }
        $this->components->info('Restarting PM2 processes');
        $process = new Process(['pm2', 'restart', 'all', '--update-env'],  $this->getCwdOption(), timeout: 300);
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
