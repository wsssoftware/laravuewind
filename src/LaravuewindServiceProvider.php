<?php

namespace Laravuewind;

use Laravuewind\Commands\LaravuewindCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravuewindServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravuewind')
            ->hasConfigFile()
            ->hasRoutes(['filepond'])
            ->hasCommand(LaravuewindCommand::class);
    }
}
