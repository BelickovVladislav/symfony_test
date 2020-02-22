<?php


namespace App\Exception;


use Exception;
use Throwable;

class InvalidDataException extends Exception implements Throwable
{
    private $rawData;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(is_string($message) ? $message: '', $code, $previous);
        $this->rawData = $message;

    }

    public function getRawData()
    {
        return $this->rawData;
    }
}
