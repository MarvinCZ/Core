<?php

namespace Core\Exceptions;


class ServiceNotRegistered extends \Exception
{

    /**
     * UndefinedServiceException constructor.
     * @param string $serviceName
     */
    public function __construct($serviceName)
    {
        parent::__construct("Service {$serviceName} is not defined");
    }
}
