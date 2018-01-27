<?php

namespace Core;

use Core\Controller\BaseController;
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

        /** @var BaseController $controller */
        $controller = $this->container->buildByType($controller);
        $controller->setApplication($this);
        $controller->init();
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
    	if ($e instanceof RouteNotFound) {
    		$this->redirect('');
	    }
        throw $e;
    }

	public function redirect($string)
	{
		header('Location: http://' . $_SERVER['HTTP_HOST'] . $string);
		die();
	}
}
