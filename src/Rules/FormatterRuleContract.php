<?php

namespace TLabsCo\Formatter\Rules;

use TLabsCo\Formatter\Formatter;

interface FormatterRuleContract
{

    /**
     * @param Formatter $formatter
     * @return mixed
     */
    public function setFormatter(Formatter $formatter);

    /**
     * @param string $attribute
     * @param mixed $value
     * @return mixed
     */
    public function format($attribute, $value);
}
