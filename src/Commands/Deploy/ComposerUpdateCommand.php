<?php

namespace Laravuewind\Commands\Deploy;

use Composer\Console\Application;
use Composer\Util\Platform;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArrayInput;

class ComposerUpdateCommand extends Command
{
    public $signature = 'composer:update';

    public $description = 'Update composer dependencies';

    public function handle(): int
    {
        ini_set('memory_limit', '-1');
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $args = ['command' => 'update'];
        if (app()->isProduction()) {
            $this->components->warn('Running Composer update in production mode...');
            $args['--optimize-autoloader'] = true;
            $args['--no-dev'] = true;
            Platform::putEnv('COMPOSER_ALLOW_SUPERUSER', '1');
        } else {
            $this->components->info('Running Composer update...');
        }
        $input = new ArrayInput($args);

        try {
            $application->run($input);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return self::SUCCESS;
    }
}
