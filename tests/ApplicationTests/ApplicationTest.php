<?php

namespace Tests\ApplicationTests;

use Core\Application;
use Core\Exceptions\RouteNotFound;
use Core\Router;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testSimpleRoute()
    {
        $app = $this->buildApplication('GET', '/news/');
        $this->expectException(IndexCalledException::class);
        $app->run();
    }

    public function testParamRoute()
    {
        $app = $this->buildApplication('GET', '/news/13');
        $this->expectException(ShowCalledException::class);
        $app->run();
    }

    public function testNoRoute()
    {
        $app = $this->buildApplication('GET', '/newws/{id}');
        $this->expectException(RouteNotFound::class);
        $app->run();
    }

    private function buildApplication($method, $route): Application
    {

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $route;
        $app = new Application();
        $container = $app->getContainer();
        /** @var Router $router */
        $router = $container->getByType(Router::class);
        $router->registerFile(__DIR__ . '/routes.php');
        return $app;
    }
}
