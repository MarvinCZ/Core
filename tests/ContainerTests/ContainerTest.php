<?php

namespace Tests\ContainerTests;

use Core\DI\Container;
use Core\Exceptions\CircularDependencyFound;
use Core\Exceptions\ServiceNotRegistered;
use Core\Exceptions\ServiceParameterWithoutTypeHint;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testSimpleService()
    {
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());
        $service = $container->getByType(TestService::class);
        $this->assertInstanceOf(TestService::class, $service);
    }

    public function testServiceWithDependency()
    {
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());

        /** @var ServiceWithDependency $service */
        $service = $container->getByType(ServiceWithDependency::class);
        $this->assertInstanceOf(ServiceWithDependency::class, $service);
        $this->assertInstanceOf(TestService::class, $service->getService());
    }

    public function testInterfaceService()
    {
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());
        $service = $container->getByType(ITestService::class);
        $this->assertInstanceOf(TestService::class, $service);
    }

    public function testNonExistingService()
    {
        $this->expectException(ServiceNotRegistered::class);
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());
        $container->getByType("App\XXX");
    }

    public function testCircular()
    {
        $this->expectException(CircularDependencyFound::class);
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());
        $container->getByType(A::class);
    }

    public function testNoTypeHint()
    {
        $this->expectException(ServiceParameterWithoutTypeHint::class);
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());
        $container->getByType(ServiceWithParameter::class);
    }

    public function testInheritanceWithDependency()
    {
        $container = new Container();
        $container->addServiceProvider(new TestServiceProvider());

        /** @var ServiceWithDependency $service */
        $service = $container->getByType(InheritedClass::class);
        $this->assertInstanceOf(InheritedClass::class, $service);
        $this->assertInstanceOf(TestService::class, $service->getService());
    }
}
