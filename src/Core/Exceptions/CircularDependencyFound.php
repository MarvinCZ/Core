<?php

namespace Core\Exceptions;

class CircularDependencyFound extends \Exception
{
    public function __construct($serviceName)
    {
        parent::__construct("Circular dependency found in {$serviceName}");
    }
}
