<?php

namespace TLabsCo\Formatter\Rules;

use TLabsCo\Formatter\Formatter;

abstract class FormatterRule implements FormatterRuleContract
{
    /** @var Formatter */
    protected $formatter;

    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    abstract public function format($attribute, $value);
}
