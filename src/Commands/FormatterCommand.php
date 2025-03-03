<?php

namespace TLabsCo\Formatter\Commands;

use Illuminate\Console\Command;

class FormatterCommand extends Command
{
    public $signature = 'formatter';

    public $description = 'Formatter Command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
