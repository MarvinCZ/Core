<?php

namespace Core\DI;

use Core\Exceptions\CircularDependencyFound;
use Core\Exceptions\ServiceNotRegistered;
use Core\Exceptions\UndefinedServiceException;
use Core\Exceptions\ServiceParameterWithoutTypeHint;

class Container
{
	private $services = [];
	private $building = [];

	/** @var IServiceProvider[] */
	private $serviceProviders = [];

	public function __construct()
	{
		$this->services[self::class] = $this;
		$this->addServiceProvider(new BaseServiceProvider());
	}

	public function addServiceProvider(IServiceProvider $provider)
	{
		$this->serviceProviders []= $provider;
	}

	public function getByType($type)
	{
		if (isset($this->services[$type])) {
			return $this->services[$type];
		}

		$className = null;
		foreach ($this->serviceProviders as $serviceProvider) {
			$services = $serviceProvider->getServices();
			if (isset($services[$type])) {
				$className = $services[$type];
				break;
			}
		}

		if ($className == NULL) {
			throw new ServiceNotRegistered($type);
		}

		if (!class_exists($className)) {
			throw new UndefinedServiceException($type);
		}

		$service = $this->buildService($className);
		$this->services[$type] = $service;
		$this->services[$className] = $service;

		return $service;
	}

	private function buildService($type)
	{
		if (isset($this->building[$type])) {
			throw new CircularDependencyFound($type);
		}
		$this->building[$type] = true;

		$classReflection = new \ReflectionClass($type);
		$constructor = $classReflection->getConstructor();
		$requiredParameters = $constructor ? $constructor->getParameters() : [];
		$parameters = [];
		foreach ($requiredParameters as $parameter) {
			$parameterType = $parameter->getClass();
			if (!$parameterType) {
				throw new ServiceParameterWithoutTypeHint($type);
			}
			$parameterType = $parameterType->name;
			$parameters []= $this->getByType($parameterType);
		}

		unset($this->building[$type]);
		return $classReflection->newInstanceArgs($parameters);
	}
}