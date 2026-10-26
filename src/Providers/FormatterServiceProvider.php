<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Providers;

use Illuminate\Support\ServiceProvider;

class FormatterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/formatter.php',
            'formatter'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/formatter.php' => config_path('formatter.php'),
        ], 'formatter-config');
    }
}
