<?php

namespace TLabsCo\Formatter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TLabsCo\Formatter\Commands\FormatterCommand;

class FormatterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('formatter')
            ->hasConfigFile()
            ->hasCommand(FormatterCommand::class);
    }
}
