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

        if ($className == null) {
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

    public function buildByType($type)
    {
        $service = $this->buildService($type);
        $reflection = new \ReflectionClass($type);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $methodName = $method->name;
            if (preg_match('/^inject/', $methodName)) {
                $parameters = $method->getParameters();
                if (count($parameters) != 1) {
                    throw new \Exception();
                }
                $par = $parameters[0]->getClass();
                if (!$par) {
                    throw new ServiceParameterWithoutTypeHint($methodName);
                }
                $par = $par->name;
                $value = $this->getByType($par);
                $service->$methodName($value);
            }
        }
        return $service;
    }

    public function runMethod($instance, $method, $params)
    {
        $reflection = new \ReflectionClass(get_class($instance));
        $reflectionMethod = $reflection->getMethod($method);
        $requiredParameters = $reflectionMethod->getParameters();
        $parameters = [];
        foreach ($requiredParameters as $parameter) {
            if (isset($params[$parameter->name])) {
                $parameters []= $params[$parameter->name];
            } else {
                $parameterType = $parameter->getClass();
                if (!$parameterType) {
                    throw new ServiceParameterWithoutTypeHint($instance);
                }
                $parameterType = $parameterType->name;
                $parameters []= $this->getByType($parameterType);
            }
        }
        $instance->$method(...$parameters);
    }
}
