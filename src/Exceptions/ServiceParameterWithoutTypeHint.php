<?php

namespace Core\Exceptions;

class ServiceParameterWithoutTypeHint extends \Exception
{
    public function __construct($serviceName)
    {
        parent::__construct("Service {$serviceName} is not defined");
    }
}
