<?php

namespace Laravuewind;

use Illuminate\Foundation\Application;
use Laravuewind\Commands\Deploy\ComposerUpdateCommand;
use Laravuewind\Commands\Deploy\GitPullCommand;
use Laravuewind\Commands\Deploy\NpmUpdateCommand;
use Laravuewind\Commands\Deploy\ViteBuildCommand;
use Laravuewind\Commands\LaravuewindCommand;
use Laravuewind\FilePond\FilePond;
use Laravuewind\FilePond\FilePondFactory;
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
            ->hasCommand(LaravuewindCommand::class)
            ->hasCommand(ComposerUpdateCommand::class)
            ->hasCommand(GitPullCommand::class)
            ->hasCommand(NpmUpdateCommand::class)
            ->hasCommand(ViteBuildCommand::class)
            ->hasTranslations();

        $langPath = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'lang';
        $this->loadTranslationsFrom($langPath, 'laravue');

        $this->publishes([
            $langPath => $this->app->langPath('vendor/laravue'),
        ], 'laravue-toolkit-lang');
    }

    public function boot(): self
    {
        $this->app->bind(FilePondFactory::class, function (Application $app) {
            return new FilePondFactory();
        });
        $this->app->bind(FilePond::class, function (Application $app) {
            return new FilePond($app->make(FilePondFactory::class));
        });

        return parent::boot();
    }
}
