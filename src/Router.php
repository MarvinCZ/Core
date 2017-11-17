<?php

namespace Core;

use Core\Http\Request;

class Router
{

	/** @var Route[] */
	private $routes = [];
	/** @var Request */
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function registerFile($file)
	{
		include($file);
	}

	public function getMethod()
	{
		if ($_SERVER['REQUEST_METHOD'] == RestMethods::GET) {
			return RestMethods::GET;
		}

		if (isset($_POST['method']) && $_POST['method'] == RestMethods::DELETE) {
			return RestMethods::DELETE;
		}

		return RestMethods::POST;
	}

	public function addRoute($method, $url, $controller, $action) : Route
	{
		$route = new Route($method, $url, $controller, $action);
		$this->routes []= $route;
		return $route;
	}

	public function addGet($url, $controller, $action) : Route
	{
		return $this->addRoute(RestMethods::GET, $url, $controller, $action);
	}

	public function addPost($url, $controller, $action) : Route
	{
		return $this->addRoute(RestMethods::POST, $url, $controller, $action);
	}

	public function addDelete($url, $controller, $action) : Route
	{
		return $this->addRoute(RestMethods::DELETE, $url, $controller, $action);
	}

	public function matches() : ?Route
	{
		foreach ($this->routes as $route) {
			if ($route->matches($this->request)) {
				return $route;
			}
		}

		return NULL;
	}
}