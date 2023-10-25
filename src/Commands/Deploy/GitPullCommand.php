<?php

namespace Laravuewind\Commands\Deploy;

use CzProject\GitPhp\Git;
use Illuminate\Console\Command;

class GitPullCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:pull {--c|cwd= : Current work directory for command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Git git pull to get all updates';

    /**
     * Execute the console command.
     *
     *
     * @throws \CzProject\GitPhp\GitException
     */
    public function handle(): int
    {
        $this->components->info('Running GIT git pull');
        $cwd = $this->option('cwd') ?? dirname(__DIR__, 6);
        if (! is_dir($cwd)) {
            $this->components->error("The directory $cwd does not exist");

            return self::FAILURE;
        }

        $repository = (new Git())->open($cwd);
        if ($repository->hasChanges()) {
            if (
                ! app()->isProduction() &&
                ! $this->components->confirm('There are changes in the repository and it will be lost. Do you want to continue?', false)
            ) {
                $this->components->warn('Skipped.');

                return self::SUCCESS;
            } else {
                $this->components->warn('There are changes in the repository, cleaning it...');
            }
            foreach ($repository->checkout('.') as $output) {
                $this->components->info('GIT: '.$output);
            }
        }
        foreach ($repository->execute('pull', 'origin', 'main') as $output) {
            $this->components->info('GIT: '.$output);
        }

        return self::SUCCESS;
    }
}
