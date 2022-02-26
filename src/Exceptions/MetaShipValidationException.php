<?php

namespace LapayGroup\MetaShipSdk\Exceptions;

class MetaShipValidationException extends \InvalidArgumentException
{
    /**
     * @var array
     */
    private $validation_errors = null;

    public function __construct($message = "", $code = 0, $validation_errors = null, $previous = null)
    {
        $this->validation_errors = $validation_errors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validation_errors;
    }
}