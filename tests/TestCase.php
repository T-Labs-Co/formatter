<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TLabsCo\Formatter\Providers\FormatterServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FormatterServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        //
    }
}
