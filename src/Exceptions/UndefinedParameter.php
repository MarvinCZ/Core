<?php

namespace Core\Exceptions;

class UndefinedParameter extends \Exception
{
    public function __construct($parameterName)
    {
        parent::__construct("Parameter {$parameterName} is not defined");
    }
}
