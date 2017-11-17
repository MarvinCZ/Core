<?php

namespace Tests\ContainerTests;

class TestService implements ITestService
{
    public function test()
    {
        return "hey";
    }

    public function get()
    {
        return null;
    }
}