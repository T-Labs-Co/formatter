<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Contracts;

use TLabsCo\Formatter\Formatter;

interface FormatterRuleContract
{
    public function setFormatter(Formatter $formatter): void;

    public function format(string $attribute, mixed $value): mixed;
}
