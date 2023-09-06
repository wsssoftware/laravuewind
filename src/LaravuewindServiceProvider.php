<?php

namespace Laravuewind;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Laravuewind\Commands\LaravuewindCommand;

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
            ->hasViews()
            ->hasMigration('create_laravuewind_table')
            ->hasCommand(LaravuewindCommand::class);
    }
}