<?php

namespace Tests\ContainerTests;

class ServiceWithDependency
{
    /**
     * @var TestService
     */
    private $service;

    public function __construct(TestService $service)
    {
        $this->service = $service;
    }

    /**
     * @return TestService
     */
    public function getService(): TestService
    {
        return $this->service;
    }
}