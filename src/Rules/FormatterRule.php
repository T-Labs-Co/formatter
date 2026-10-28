<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Rules;

use TLabsCo\Formatter\Contracts\FormatterRuleContract;
use TLabsCo\Formatter\Formatter;

abstract class FormatterRule implements FormatterRuleContract
{
    protected Formatter $formatter;

    public function setFormatter(Formatter $formatter): void
    {
        $this->formatter = $formatter;
    }

    abstract public function format(string $attribute, mixed $value): mixed;
}
