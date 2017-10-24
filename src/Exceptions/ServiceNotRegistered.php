<?php

namespace Core\Exceptions;


class ServiceNotRegistered extends \Exception
{

	/**
	 * UndefinedServiceException constructor.
	 */
	public function __construct($serviceName)
	{
		parent::__construct("Service {$serviceName} is not defined");
	}
}