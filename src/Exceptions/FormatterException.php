<?php

declare(strict_types=1);

namespace TLabsCo\Formatter\Exceptions;

use Exception;

class FormatterException extends Exception
{
    public function __construct(
        string $message = '[Formatter] - Unexpected value format',
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
