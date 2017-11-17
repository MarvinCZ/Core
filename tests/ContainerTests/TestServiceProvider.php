<?php

namespace Tests\ContainerTests;

use Core\DI\IServiceProvider;

class TestServiceProvider implements IServiceProvider
{

    public function getServices()
    {
        return [
            TestService::class => TestService::class,
            ITestService::class => TestService::class,
            ServiceWithDependency::class => ServiceWithDependency::class,
            A::class => A::class,
            B::class => B::class,
            C::class => C::class,
            ServiceWithParameter::class => ServiceWithParameter::class,
            InheritedClass::class => InheritedClass::class,
        ];
    }
}
