<?php

namespace IDDRS\SIAPC\PAD\Converter\Exception;

use Exception;
use Throwable;

class WarningException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}