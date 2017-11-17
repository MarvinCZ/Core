<?php

namespace Core;

use Core\DI\Container;
use Core\Exceptions\RouteNotFound;

class Application
{

    /** @var Container */
    private $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function run()
    {
        try {
            /** @var Router $router */
            $router = $this->container->getByType(Router::class);
            $route = $router->matches();

            if (!$route) {
                throw new RouteNotFound();
            }

            $this->dispatchRoute($route);

        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    private function dispatchRoute(Route $route)
    {
        $controller = $route->getController();
        $action = $route->getAction();

        $controller = $this->container->buildByType($controller);
        $params = $route->getParams();

        $this->container->runMethod($controller, $action, $params);
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    private function handleException($e)
    {
        throw $e;
    }
}