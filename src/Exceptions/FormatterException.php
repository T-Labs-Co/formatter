<?php
namespace TLabsCo\Formatter\Exceptions;

use \Exception;

class FormatterException extends Exception
{
    public function __construct($message = '[Formatter] - Unexpectation value format', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
