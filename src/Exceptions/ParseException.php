<?php namespace HSkrasek\OpenAPI\Exceptions;

use Exception;
use Throwable;

class ParseException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Unable to parse file. Reason: %s', $message), $code, $previous);
    }
}
